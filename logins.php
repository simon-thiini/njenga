<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom CSS */
        .card {
            width: 300px;
            margin: 0 auto;
            margin-top: 50px;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>

<body>
    <?php
    // Start session
    session_start();
    
    // Display success message if set
    if (isset($_SESSION['success'])) {
        echo '<div class="alert alert-success" role="alert">' . $_SESSION['success'] . '</div>';
        unset($_SESSION['success']);
    }
    ?>

    <div class="card">
        <div class="card-body">
            <h2 class="card-title text-center">Login Form</h2>
            <form action="login.php" method="post">
                <div class="form-group">
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
            <p class="text-center mt-3">Don't have an account? <a href="registration.html">Register</a></p>
        </div>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>