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

    <!-- Welcome Message -->
    <div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h2>Welcome back, Admin!</h2>
            <p>We're glad to have you manage the store's backend. Here you can easily view, edit, and manage all aspects of the website.</p>
        </div>
    </div>


</body>

</html>
