<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login and Registration</title>
    <link rel="stylesheet" type="text/css" href="styles.css">   
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <header>
        <h2 class="logo">Logo</h2>
        <nav class="navigation">
            <a href="index.html">Home</a>
            <a href="about.html">About</a> 
            <a href="services.html">Services</a>
            <a href="contact.html">Contact</a>
            <button class="btnLogin-popup">Login</button>
        </nav>
    </header>

    <div class="wrapper">
        <span class="icon-close"><ion-icon name="close-circle-sharp"></ion-icon></span>
        <div class="form-box login">
            <h2>Login</h2>
            <form action="login.php" method="post">
                <div class="input-box">
                    <span class="icon"><ion-icon name="mail-sharp"></ion-icon></span>
                    <input type="email" name="email" required>
                    <label>Email</label>
                </div>
                <div class="input-box">
                    <span class="icon"><ion-icon name="lock-closed-sharp"></ion-icon></span>
                    <input type="password" name="password" required>
                    <label>Password</label>
                </div>
                <div class="remember-forgot">
                    <label><input type="checkbox"> Remember me</label>
                    <a href="#">Forgot password?</a>
                </div>
                <button type="submit" class="btn">Login</button>
                <div class="login-register">
                    <p>Don't have an account?<a href="#" class="register-link">Register</a></p>
                </div>
            </form>
        </div>

        <div class="form-box register">
            <h2>Registration</h2>
            <form action="register.php" method="post">
                <div class="input-box">
                    <span class="icon"><ion-icon name="mail-sharp"></ion-icon></span>
                    <input type="email" required name="email">
                    <label>Email</label>
                </div>
                <div class="input-box">
                    <span class="icon"><ion-icon name="person-circle-sharp"></ion-icon></span>
                    <input type="text" required name="username">
                    <label>Username</label>
                </div>
                <div class="input-box">
                    <span class="icon"><ion-icon name="lock-closed-sharp"></ion-icon></span>
                    <input type="password" required name="password">
                    <label>Password</label>
                </div>
                <div class="remember-forgot">
                    <label><input type="checkbox">
                    I agree to the terms and conditions</label>
                </div>
                <button type="submit" class="btn">Register</button>
                <div class="login-register">
                    <p>Already have an account?<a href="#" class="login-link">Login</a></p>
                </div>
            </form>
        </div>
    </div>
    <script src="script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>







<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Function to check if a user already exists (dummy function for now)
function user_exists($email, $username) {
    // Dummy function assuming no user exists
    return false;
}

// Function to insert a new user into the database
function insert_new_user($email, $username, $password) {
    // Connect to the database
    $con = mysqli_connect("localhost", "root", "", "registration");

    // Check connection
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit;
    }

    // Prepare SQL statement
    $query = "INSERT INTO register_customer (email, username, password) VALUES (?, ?, ?)";
    
    // Bind parameters and execute query
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "sss", $email, $username, $password);
    $success = mysqli_stmt_execute($stmt);

    // Check for errors
    if ($success) {
        // Insertion successful
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return true;
    } else {
        // Insertion failed
        echo "SQL Error: " . mysqli_error($con);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return false;
    }
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email']) ? $_POST['email'] : "";
    $username = isset($_POST['username']) ? $_POST['username'] : "";
    $password = isset($_POST['password']) ? $_POST['password'] : "";

    // Validate email, username, and password (basic validation)
    if (empty($email) || empty($username) || empty($password)) {
        $_SESSION['error_message'] = "Please fill in all fields.";
        header("Location: register.php");
        exit;
    }

    // Attempt to insert the new user
    if (insert_new_user($email, $username, $password)) {
        $_SESSION['success_message'] = "Registration successful! Please log in.";
        header("Location: success.html"); // Redirect to success.html
        exit;
    } else {
        $_SESSION['error_message'] = "Registration failed. Please try again.";
        header("Location: register.php");
        exit;
    }
}
?>
