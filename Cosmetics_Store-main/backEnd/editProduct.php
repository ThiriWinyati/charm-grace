<?php
    require_once "../db_connect.php";

    if(!isset($_SESSION)) {
        session_start();
    }

    try{
        //to get categories
        $sql = "select * from categories";
        $stmt = $conn->query($sql);
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //to get brands
        $sql = "select * from brands";
        $stmt = $conn->query($sql);
        $brands = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //to get admins
        $sql = "select * from admin_users";
        $stmt = $conn->query($sql);
        $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }catch(PDOException $e) {
        echo $e->getMessage();
    }

    if(isset($_GET['id'])) {
        $productId = $_GET['id'];
        $product = getProductInfo($productId);
    }

    function getProductInfo($productId) {
        global $conn;
    
        // Query for product details
        $sql = "SELECT 
                p.Product_ID, p.Name AS productName, c.Category_ID AS categories, 
                p.Price, p.Stock_Quantity, a.Admin_User_ID AS admin_users, 
                b.brand_id AS brands, p.Description 
            FROM products p
            JOIN categories c ON p.Category_ID = c.Category_ID
            JOIN admin_users a ON p.Admin_User_ID = a.Admin_User_ID
            JOIN brands b ON p.brand_id = b.brand_id
            WHERE p.Product_ID=?";
    
        $stmt = $conn->prepare($sql);
        $stmt->execute([$productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        // Query for product images
        $imageSql = "SELECT image_path FROM product_images WHERE Product_ID = ?";
        $imageStmt = $conn->prepare($imageSql);
        $imageStmt->execute([$productId]);
        $product['images'] = $imageStmt->fetchAll(PDO::FETCH_COLUMN);

        return $product;
    }
    


    if (isset($_POST['update'])) {
        $productId = $_POST['Product_ID'];
        $productName = trim($_POST['productName']);
        $category = $_POST['category'];
        $price = trim($_POST['price']);
        $quantity = trim($_POST['quantity']);
        $admin = $_POST['admin'];
        $brand = $_POST['brand'];
        $description = trim($_POST['description']);
        $uploadedImages = $_FILES['product_images'];
        $imagePaths = [];
    
        for ($i = 0; $i < count($uploadedImages['name']); $i++) {
            $filename = basename($uploadedImages['name'][$i]);
            $uploadPath = "../uploads/products/" . $filename;
    
            if (move_uploaded_file($uploadedImages['tmp_name'][$i], $uploadPath)) {
                $imagePaths[] = $uploadPath;
            } else {
                echo "Failed to upload image: " . $filename;
            }
        }
    
        // Input validation
        if (empty($productId) || empty($productName) || empty($category) || empty($price) ||
            empty($quantity) || empty($admin) || empty($brand) || empty($description)) {
            echo "All fields are required.";
            return;
        }
    
        if (!is_numeric($price) || !is_numeric($quantity)) {
            echo "Price and quantity must be numeric.";
            return;
        }
    
        try {
            $sql = "UPDATE products SET Name=?, Category_ID=?, Price=?, 
                    Stock_Quantity=?, Admin_User_ID=?, brand_id=?, Description=? 
                    WHERE Product_ID=?";
            $stmt = $conn->prepare($sql);
            $status = $stmt->execute([$productName, $category, $price, $quantity, $admin, $brand, $description, $productId]);

            // Handle image deletion
        if (isset($_POST['delete_images'])) {
            foreach ($_POST['delete_images'] as $image) {
                $filePath = "../uploads/" . $image;
                if (file_exists($filePath)) {
                    unlink($filePath); // Delete image from the server
                }

                // Remove image from the database
                $deleteSql = "DELETE FROM product_images WHERE image_path = ?";
                $deleteStmt = $conn->prepare($deleteSql);
                $deleteStmt->execute([$image]);
            }
        }

            $sql1 = "INSERT INTO product_images (product_id, image_path) VALUES (?, ?)";
            $stmt1 = $conn->prepare($sql1);
            foreach ($imagePaths as $path) {
                $stmt1->execute([$productId, $path]);
            }
    
            if ($status) {
                $_SESSION['updateProductSuccess'] = "Product with ID $productId has been updated successfully";
                header("Location:viewProduct.php");
                exit();
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../backEnd/backEnd_css/style.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../backEnd/backend_Javascript/insert.js"></script>
    <script src="../backEnd/backend_Javascript/sidebar.js"></script>
    <link rel="icon" href="path/to/favicon.ico">
    <title>Edit Products Page</title>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <nav class="nav flex-column">
            <a href="../backEnd/adminDashboard.php" class="nav-link active">
                <i class="fa fa-home"></i> Dashboard
            </a>
            <a href="#" class="nav-link">
                <i class="fa fa-users"></i> Customers
            </a>
            <a href="#" class="nav-link">
                <i class="fa fa-star"></i> Reviews
            </a>

            <!-- Manage Payments -->
            <a href="#" class="nav-link" onclick="toggleDropdown('paymentsDropdown')">
                <i class="fa fa-credit-card"></i> Manage Payments <i class="fa fa-caret-down ms-auto"></i>
            </a>
            <div id="paymentsDropdown" class="dropdown-container">
                <a href="#" class="nav-link">View Payment Methods</a>
                <a href="#" class="nav-link">Edit Payment Methods</a>
            </div>

            <!-- Manage Products -->
            <a href="#" class="nav-link" onclick="toggleDropdown('productsDropdown')">
                <i class="fa fa-cube"></i> Manage Products <i class="fa fa-caret-down ms-auto"></i>
            </a>
            <div id="productsDropdown" class="dropdown-container">
                <a href="viewProduct.php" class="nav-link">View Products</a>
                <a href="editProduct.php" class="nav-link">Edit Products</a>
                <a href="insertProduct.php" class="nav-link">Insert New Products</a>
            </div>

            <!-- Manage Orders -->
            <a href="#" class="nav-link" onclick="toggleDropdown('ordersDropdown')">
                <i class="fa fa-shopping-cart"></i> Manage Orders <i class="fa fa-caret-down ms-auto"></i>
            </a>
            <div id="ordersDropdown" class="dropdown-container">
                <a href="#" class="nav-link">View Orders</a>
                <a href="#" class="nav-link">Edit Orders</a>
            </div>

            <!-- Manage Coupons -->
            <a href="#" class="nav-link" onclick="toggleDropdown('couponsDropdown')">
                <i class="fa fa-tags"></i> Manage Coupons <i class="fa fa-caret-down ms-auto"></i>
            </a>
            <div id="couponsDropdown" class="dropdown-container">
                <a href="#" class="nav-link">View Coupons</a>
                <a href="#" class="nav-link">Edit Coupons</a>
            </div>

            <a href="#" class="nav-link">
                <i class="fa fa-check-square"></i> To-Do List
            </a>
            <a href="#" class="nav-link">
                <i class="fa fa-calendar"></i> Calendar
            </a>
        </nav>

        <!-- Logout -->
        <div class="logout">
            <a href="logout.html" class="nav-link">
                <i class="fa fa-sign-out"></i> Logout
            </a>
        </div>
    </div>


    <!-- Main Content -->
    <div id="main">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light d-flex align-items-center">
    <div class="container-fluid d-flex align-items-center">
        <!-- Sidebar Toggle Button -->
        <button id="openNav" class="btn btn-outline-primary me-3" onclick="toggleSidebar()">&#9776;</button>

        <!-- Logo and Brand Name -->
        <div class="d-flex align-items-center">
            <img src="../images/logo.png" alt="Logo" style="width: 50px; height: auto; object-fit: contain;">
            <a href="../backEnd/adminHome.php" style="text-decoration: none"><h5 class="ms-2 mb-0 brand-name">Charm & Grace</h5></a>
        </div>

        <!-- Search Box -->
        <div class="d-flex ms-auto">
            <button class="search" type="submit">
                <i class="fa fa-search"></i>
            </button>
        </div>

        <!-- Notification Bell -->
        <a href="#toDoList" 
            class="position-relative text-decoration-none notification-bell" 
            style="color: inherit; margin-left: 20px;">
            <i class="fa fa-bell fs-5"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                3 <!-- Example notification count -->
            </span>
        </a>
    </div>
</nav>
        <div class="container mt-4">

    <!-- Edit Products View -->
    <div class="container">
        <h2 class="text-center mb-4">Edit Products</h2>
        <form method="post" action="<?php $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">
            <input type="hidden" name="Product_ID" value="<?php if(isset($product['Product_ID'])) echo $product['Product_ID']; ?>">


    
                <div class="mb-3">
                    <label for="productName" class="form-label">Product Name</label>
                    <input type="text" class="form-control" name="productName" 
                    value="<?php if(isset($product['productName'])) echo $product['productName'] ?>">
                </div>
     

                <div class="mb-3">
                    <?php
                    if (isset($product['categories'])) {
                        foreach ($categories as $category) {
                            if ($category['Category_ID'] == $product['categories']) {
                                echo "You have selected: " . $category['Category_Name'];
                                break; // Stop the loop once the matching category is found
                            }
                        }
                    }
                    ?>
                    <label for="category" class="form-label">Category</label>
                    <select class="form-select" name="category">
                        <option value="" disabled>Choose Category</option>
                        <?php
                        if (isset($categories)) {
                            foreach ($categories as $category) {
                                // Check if this category is the selected one
                                $selected = ($category['Category_ID'] == $product['categories']) ? "selected" : "";
                                echo "<option value='$category[Category_ID]' $selected>$category[Category_Name]</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="text" class="form-control" name="price" 
                    value="<?php if(isset($product['Price'])) echo $product['Price'] ?>">
                </div>

                <div class="mb-3">
                    <label for="quantity" class="form-label">Stock Quantity</label>
                    <input type="text" class="form-control" name="quantity" 
                    value="<?php if(isset($product['Stock_Quantity'])) echo $product['Stock_Quantity'] ?>">
                </div>

                <div class="mb-3">
                    <?php
                    if (isset($product['admin_users'])) {
                        foreach ($admins as $admin) {
                            if ($admin['Admin_User_ID'] == $product['admin_users']) {
                                echo "You have selected: " . $admin['Name'];
                                break; // Stop the loop once the matching category is found
                            }
                        }
                    }
                    ?>
                    <label for="admin" class="form-label">Admin</label>
                    <select class="form-select" name="admin">
                        <option value="" disabled>Choose Admin</option>
                        <?php
                        if (isset($admins)) {
                            foreach ($admins as $admin) {
                                // Check if this category is the selected one
                                $selected = ($admin['Admin_User_ID'] == $product['admin_users']) ? "selected" : "";
                                echo "<option value='$admin[Admin_User_ID]' $selected>$admin[Name]</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <?php
                    if (isset($product['brands'])) {
                        foreach ($brands as $brand) {
                            if ($brand['brand_id'] == $product['brands']) {
                                echo "You have selected: " . $brand['brand_name'];
                                break; // Stop the loop once the matching category is found
                            }
                        }
                    }
                    ?>
                    <label for="brand" class="form-label">Brand</label>
                    <select class="form-select" name="brand">
                        <option value="" disabled>Choose Brand</option>
                        <?php
                        if (isset($brands)) {
                            foreach ($brands as $brand) {
                                // Check if this category is the selected one
                                $selected = ($brand['brand_id'] == $product['brands']) ? "selected" : "";
                                echo "<option value='$brand[brand_id]' $selected>$brand[brand_name]</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" class="form-control" name="description" 
                    value="<?php if(isset($product['Description'])) echo $product['Description'] ?>">
                </div>

                <div class="mb-3">
                <label for="currentImages" class="form-label">Current Images</label>
                <div id="currentImages">
                    <?php if (isset($product['images']) && !empty($product['images'])): ?>
                        <?php foreach ($product['images'] as $image): ?>
                            <div class="d-flex align-items-center mb-2">
                                <img src="../uploads/<?php echo $image; ?>" alt="Product Image" style="width: 100px; height: auto; margin-right: 10px;">
                                <input type="checkbox" name="delete_images[]" value="<?php echo $image; ?>"> Delete
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No images available.</p>
                    <?php endif; ?>
                </div>
            </div>

                <!-- Number of Images -->
                <div class="mb-3">
                    <label for="imageCount" class="form-label">Number of Images</label>
                    <input type="number" class="form-control" id="imageCount" name="image_count" min="1" max="10" required>
                </div>

                <!-- Dynamic Image Uploads -->
                <div id="imageUploadContainer" class="mb-3">
                    <label class="form-label">Upload Product Images</label>
                    <div id="imageInputs">
                    <input type="file" class="form-control mb-2" name="product_images[]" multiple required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" name="update">Update</button>



                    
   
        </form>

    </div>
</body>
</html>