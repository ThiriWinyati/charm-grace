<?php
require_once "../db_connect.php";


if (!isset($_SESSION)) {
    session_start(); // to create session if not exist
}

function isPasswordStrong($password)
{
    if (strlen($password) < 8) {
        return false;
    } elseif (isstrong($password)) {
        return true;
    } else
        return false;
}

if (isset($_POST['login']) && $_SERVER['REQUEST_METHOD'] == "POST") 
{

    $name = $_POST["name"];
    $password = $_POST["password"];

    if(strlen($password)>7)
    {

        try {
                $sql = "select password from admin_users where name = ? ";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$name]);
                $info = $stmt ->fetch(PDO::FETCH_ASSOC);
                if ($info) {
                    $password_hash = $info['password'];
                    if(password_verify($password, $password_hash))
                    {
                        $_SESSION['adminLoginSuccess'] ="Login Success";
                        $_SESSION['isLoggedIn'] = true;
                        header("Location:adminDashboard.php");
                    }
                    else{
                    $password_err = "Email or Password is incorrect";
                    }
                }

            } catch (PDOException $e) {
                echo $e->getMessage();
            } //end catch
        } //str len
        else{
            $password_err = "Email or Password is incorrect";
        }
} // the whole if end
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../backEnd/backend_css/style.css">
    <!-- Bootstrap CSS (Optional) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Admin Login</h4>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                        <?php if(isset($password_err))
                            {
                                echo "<p class = 'alert alert-danger'>$password_err</p>";
                            }
                        ?>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="name" id="username" class="form-control" placeholder="Enter your username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                            </div>
                            <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <small>&copy; 2024 Charm & Grace</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
