<?php
// Start session
session_start();

// Include database configuration
include 'configut.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate and sanitize inputs (not implemented in this example)

    // Check if passwords match
    if ($password !== $confirm_password) {
        // Passwords do not match, redirect back to registration form with error message
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: registration.html");
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into the database
    $sql = "INSERT INTO users (full_name, username, email, password) VALUES ('$full_name', '$username', '$email', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        // Registration successful, redirect to login page
        $_SESSION['success'] = "Registration successful. You can now login.";
        header("Location: logins.php");
        exit();
    } else {
        // Error occurred during registration, redirect back to registration form with error message
        $_SESSION['error'] = "Error: " . $sql . "<br>" . $conn->error;
        header("Location: registration.html");
        exit();
    }
}
?>
