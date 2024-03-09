<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // If not logged in, redirect to login page
    header("Location: login.html");
    exit();
}

// Include database configuration
include 'configut.php';

// Initialize message variable
$message = '';

// Fetch user data from database
$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username='$username'";
$result = $conn->query($sql);
$user_data = $result->fetch_assoc();

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if delete button is clicked
    if (isset($_POST['delete'])) {
        // Delete user and all related data from the database
        $sql_delete = "DELETE FROM users WHERE username='$username'";
        if ($conn->query($sql_delete) === TRUE) {
            // Redirect to logout page after successful deletion
            header("Location: logout.php");
            exit();
        } else {
            $message = "Error deleting user: " . $conn->error;
        }
    } else {
        // Update user data
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];
        $country = $_POST['country'];
        $county = $_POST['county'];
        $sub_county = $_POST['sub_county'];

        // Update database with user data
        $sql_update = "UPDATE users SET full_name='$full_name', email='$email', phone_number='$phone_number', country='$country', county='$county', sub_county='$sub_county' WHERE username='$username'";
        if ($conn->query($sql_update) === TRUE) {
            // Refresh user data after update
            $result = $conn->query($sql);
            $user_data = $result->fetch_assoc();
            $message = "Profile updated successfully.";
        } else {
            $message = "Error updating profile: " . $conn->error;
        }
    }
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
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title text-center">User Profile</h2>
                        <!-- Display message as alert -->
                        <?php if($message): ?>
                            <div class="alert alert-<?php echo strpos($message, 'Error') !== false ? 'danger' : 'success'; ?>" role="alert">
                                <?php echo $message; ?>
                            </div>
                        <?php endif; ?>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <div class="form-group">
                                <label for="full_name">Full Name</label>
                                <input type="text" name="full_name" id="full_name" class="form-control" value="<?php echo isset($user_data['full_name']) ? $user_data['full_name'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" id="username" class="form-control" value="<?php echo $username; ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" value="<?php echo isset($user_data['email']) ? $user_data['email'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="phone_number">Phone Number</label>
                                <input type="text" name="phone_number" id="phone_number" class="form-control" value="<?php echo isset($user_data['phone_number']) ? $user_data['phone_number'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="country">Country</label>
                                <input type="text" name="country" id="country" class="form-control" value="<?php echo isset($user_data['country']) ? $user_data['country'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="county">County</label>
                                <input type="text" name="county" id="county" class="form-control" value="<?php echo isset($user_data['county']) ? $user_data['county'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="sub_county">Sub-County</label>
                                <input type="text" name="sub_county" id="sub_county" class="form-control" value="<?php echo isset($user_data['sub_county']) ? $user_data['sub_county'] : ''; ?>">
                            </div>
                            <!-- Display username -->
                            
                            <!-- Display CV download link if CV is uploaded -->
                                <p><strong>CV File uploaded:</strong> <?php echo isset($user_data['cv_path']) ? basename($user_data['cv_path']) : 'No CV uploaded'; ?></p>
                                <!-- CV upload form -->
                                <div class="form-group">
                                    <label for="cv">Upload CV (PDF, DOC, DOCX)</label>
                                    <input type="file" name="cv" id="cv" class="form-control-file" accept=".pdf,.doc,.docx">
                                </div>
                            <div class="btn-group" role="group">
                                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your account?')">Delete</button>
                                <a href="index.html" class="btn btn-success">Continue</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
