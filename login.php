<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email']) ? $_POST['email'] : "";
    $password = isset($_POST['password']) ? $_POST['password'] : "";
    
    // Attempt to authenticate user
    if (authenticate_user($email, $password)) {
        // Authentication successful, set session variables
        $_SESSION['logged_in'] = true;
        $_SESSION['email'] = $email;
        header("Location: logged.html"); // Redirect to logged.html upon successful login
        exit;
    } else {
        // Authentication failed, set error message
        $_SESSION['error_message'] = "Invalid email or password.";
        header("Location: wrong.html");
        exit;
    }
}

// Function to authenticate user credentials
function authenticate_user($email, $password) {
    // Connect to the database
    $con = mysqli_connect("localhost", "root", "", "registration");

    // Check connection
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit;
    }

    // Prepare SQL statement to fetch user details
    $query = "SELECT email, password FROM register_customer WHERE email = ?";
    $stmt = mysqli_prepare($con, $query);
    
    // Bind parameter
    mysqli_stmt_bind_param($stmt, "s", $email);
    
    // Execute query
    if (!mysqli_stmt_execute($stmt)) {
        echo "SQL Error: " . mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return false;
    }

    // Bind result variables
    mysqli_stmt_bind_result($stmt, $db_email, $db_password);

    // Fetch the result
    if (mysqli_stmt_fetch($stmt)) {
        // Verify password
        if (password_verify($password, $db_password)) {
            mysqli_stmt_close($stmt);
            mysqli_close($con);
            return true; // Password is correct, authentication successful
        }
    }

    // Close statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($con);

    return false; // Authentication failed
}
?>
