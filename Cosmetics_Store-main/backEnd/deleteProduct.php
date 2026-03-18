<?php
    require_once "../db_connect.php";

    if(isset($_SESSION)) {
        session_start();
    }

    if(isset($_GET['id'])) {
        $productId = $_GET['id'];
        $sql = "delete from products where Product_ID=?";
        $stmt = $conn->prepare($sql);
        $status = $stmt->execute([$productId]);

        if($status) {
            $_SESSION['deleteSuccess'] = "Product with ID $productId has been deleted successfully";
            header("Location: viewProduct.php");
        }
    }

?>