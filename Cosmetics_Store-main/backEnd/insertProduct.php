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

    if (isset($_POST['insert'])) {
        $name = $_POST["name"];
        $category = $_POST["category"];
        $brand = $_POST["brand"];
        $price = $_POST["price"];
        $stock_quantity = $_POST["stock_quantity"];
        $description = $_POST["description"];
        $admin = $_POST["admin"];
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
    
        try {
            // Insert product details
            $sql = "INSERT INTO products (Name, Category_ID, Price, Stock_Quantity, Admin_User_ID, Brand_ID, Description)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$name, $category, $price, $stock_quantity, $admin, $brand, $description]);
            
            // Get the last inserted product ID
            $Product_ID = $conn->lastInsertId();
    
            // Insert image paths into the product_images table
            $sql1 = "INSERT INTO product_images (product_id, image_path) VALUES (?, ?)";
            $stmt1 = $conn->prepare($sql1);
            foreach ($imagePaths as $path) {
                $stmt1->execute([$Product_ID, $path]);
            }
    
            $_SESSION['insertSuccess'] = "Product with ID $Product_ID has been inserted successfully along with its images.";
            header("Location: viewProduct.php");
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
?>

<!doctype html>
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
    <title>Admin Home Page</title>
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
            <a href="" style="text-decoration: none"><h5 class="ms-2 mb-0 brand-name">Charm & Grace</h5></a>
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

    <!-- Insert Products -->
    <div class="container">
        <h2 class="text-center mb-4">Add New Product</h2>
        <form id="addProductForm" action="insertProduct.php" method="POST" enctype="multipart/form-data">
            <!-- Product Name -->
            <div class="mb-3">
                <label for="productName" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="productName" name="name" required>
            </div>

            <!-- Category -->
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" name="category" required>
                    <option selected disabled>Select a category</option>
                    <?php
                        if(isset($categories)) {
                            foreach($categories as $category) {
                                echo "<option value = $category[Category_ID]>$category[Category_Name]</option>";
                            }
                        }
                    ?>
                </select>
            </div>

            <!-- Brand -->
            <div class="mb-3">
                <label for="brand" class="form-label">Brand</label>
                <select class="form-select" id="brand" name="brand" required>
                    <option selected disabled>Select a brand</option>
                    <?php
                        if(isset($brands)) {
                            foreach($brands as $brand) {
                                echo "<option value = $brand[brand_id]>$brand[brand_name]</option>";
                            }
                        }
                    ?>
                </select>
            </div>

            

            <!-- Price -->
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" required>
            </div>

            <!-- Stock Quantity -->
            <div class="mb-3">
                <label for="stockQuantity" class="form-label">Stock Quantity</label>
                <input type="number" class="form-control" id="stockQuantity" name="stock_quantity" required>
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label for="description" class="form-label">Product Description</label>
                <textarea id="description" name="description" class="form-control form-control-sm" rows="3" placeholder="Enter product description"></textarea>
            </div>

            <!-- Admin -->
            <div class="mb-3">
                <label for="brand" class="form-label">Admin</label>
                <select class="form-select" id="brand" name="admin" required>
                    <option selected disabled>Select Admin</option>
                    <?php
                        if(isset($admins)) {
                            foreach($admins as $admin) {
                                echo "<option value = $admin[Admin_User_ID]>$admin[Name]</option>";
                            }
                        }
                    ?>
                </select>
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

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary" name="insert">Add Product</button>
        </form>
    </div>

    <script>
        
    </script>


</body>

</html>
