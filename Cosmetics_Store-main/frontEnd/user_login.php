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
    
        $email = $_POST["email"];
        $password = $_POST["password"];
    
        if(strlen($password)>7)
        {
    
            try {
                    $sql = "select name, password from customers where email = ? ";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$email]);
                    $info = $stmt ->fetch(PDO::FETCH_ASSOC);
                    if ($info) {
                        $name = $info['name'];
                        $password_hash = $info['password'];
                        if(password_verify($password, $password_hash))
                        {
                            $_SESSION['cLoginSuccess'] = "Login Success";
                            $_SESSION['cname'] = $name;
                            $_SESSION['is_logged_in'] = true;
                            header("Location:user_homeIndex.php");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../frontEnd/frontend_css/style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="icon" href="path/to/favicon.ico">
    <title>LogIn Page</title>
</head>
<body>
<div class="signin-container">
    <div class="signin-card">
        <div class="signin-card-header">
            <h2>Sign In</h2>
        </div>
        <div class="signin-card-body">
            <form action="user_login.php" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                    <p>
                        <?php
                            if (isset($password_err)) {
                                echo "<p class ='alert alert-danger'>$password_err </p>";
                                unset($password_err);
                            }
                        ?>
                    </p>
                </div>
                <button type="submit" class="signin-btn" name="login">Log In</button>
            </form>
        </div>
        <div class="signin-card-footer">
            <p>Don't have an account? <a href="/frontEnd/user_signup.php">Sign up here</a></p>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
