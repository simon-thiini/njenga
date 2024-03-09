<?php
// Start session
session_start();

// Include database configuration
include 'configut.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate and sanitize inputs (not implemented in this example)

    // Fetch user data from the database
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // User found, verify password
        $user_data = $result->fetch_assoc();
        if (password_verify($password, $user_data['password'])) {
            // Password is correct, set session variables
            $_SESSION['username'] = $user_data['username'];
            // Redirect to index.html
            header("Location: profile.php");
            exit();
        } else {
            // Password is incorrect, redirect back to login page with error message
            $_SESSION['error'] = "Wrong login credentials.";
            header("Location: logins.php");
            exit();
        }
    } else {
        // User not found, redirect back to login page with error message
        $_SESSION['error'] = "Wrong login credentials.";
        header("Location: logins.php");
        exit();
    }
}
?>
