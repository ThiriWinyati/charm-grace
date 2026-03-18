
<?php
    require_once "../db_connect.php";

    if(!isset($_SESSION)) {
        session_start();
    }

    function isPasswordStrong($password) {
        if(strlen($password) < 8) {
            return false;
        }elseif(isStrong($password)) {
            return true;
        }else
            return false;
    }

    function isStrong($password) {
        $digitCount = 0;
        $capitalCount = 0;
        $specCount = 0;
        $lowerCount = 0;
        foreach(str_split($password) as $char){
            if(is_numeric($char)){
                $digitCount++;
            }elseif(ctype_upper($char)) {
                $capitalCount++;
            }elseif(ctype_lower($char)) {
                $lowerCount++;
            }elseif(ctype_punct($char)) {
                $specCount++;
            }
        }

        if($digitCount >= 1 && $capitalCount >= 1 && $specCount >= 1) {
            return true;
        }else{
            return false;
        }
    }

    if(isset($_POST['signUp']) && $_SERVER['REQUEST_METHOD'] == "POST"){
        $name = $_POST["name"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $confirmPassword = $_POST["confirmPassword"];

        if($password == $confirmPassword) {
            if(isPasswordStrong($password)) {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                try {
                    $sql = "insert into customers
                    (name, email, password)
                    values
                    (?,?,?)";
                    $stmt = $conn->prepare($sql);
                    $status = $stmt->execute([$name,$email,$hashedPassword]);
                    if($status) {
                        $_SESSION['signUpSuccess'] = "SignUp Successfully!";
                        header("Location:user_login.php");
                    }
                }catch(PDOException $e) {
                    echo "Error: ". $e->getMessage();
                }
            }
            else{
                $passwordError = "Password must contain at least one digit, one capital letter and one special letter";
            }
        }
        else{
            $passwordError = "Password must be at least 8 characters long.";
        }
    }


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
    <title>SignUp Page</title>
</head>
<body>
<div class="signup-container">
        <div class="signup-card">
            <div class="signup-card-header">
                <h2>Create Your Account</h2>
            </div>
            <div class="signup-card-body">
                <form action="user_signup.php" method="post">
                    <div class="mb-3">
                        <label for="fullName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="fullName" name="name" placeholder="Enter your full name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email address" required>
                    </div>
                    <div class="mb-3">
                        <p>
                            <?php
                                if(isset($passwordError)) {
                                    echo "<p class = 'alert alert-danger'>$passwordError</p>";
                                    unset($passwordError);
                                }
                            ?>
                        </p>
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Create your password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password" required>
                    </div>
                   
                    <div class="mb-3">
                    <p>By creating an account you agree to our <a href="#" style="color:dodgerblue">Terms & Privacy</a>.</p>
                    </div>
                    <button type="submit" class="signup-btn" name="signUp">Sign Up</button>
                </form>
            </div>
            <div class="signup-card-footer">
                <p>Already have an account? <a href="/frontEnd/user_login.php">Log in here</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>