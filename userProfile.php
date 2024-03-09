<?php
// Include database configuration
include 'configut.php';

// Retrieve username from URL parameter
if (isset($_GET['username'])) {
    $username = $_GET['username'];

    // Query database to retrieve user data
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, fetch user data
        $user_data = $result->fetch_assoc();
    } else {
        // User not found, handle error
        echo "User not found.";
        exit();
    }
} else {
    // Username parameter not provided, handle error
    echo "Username not specified.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">User Profile</h2>
                <?php foreach ($user_data as $key => $value): ?>
                    <p><strong><?php echo ucfirst(str_replace('_', ' ', $key)); ?>:</strong> <?php echo $value; ?></p>
                <?php endforeach; ?>
                <?php if(isset($user_data['cv_path'])): ?>
                    <p><strong>CV:</strong> <a href="<?php echo $user_data['cv_path']; ?>" target="_blank">View / Download CV</a></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
