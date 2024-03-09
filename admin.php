<?php
// Connect to the database
$servername = "127.0.0.1";
$username = "root";
$password = "";
$database = "kdfweb";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if delete request is submitted for users
if(isset($_POST['delete_user'])){
    // Get username to delete
    $username_to_delete = $_POST['delete_user'];
    
    // Prepare and execute deletion query
    $stmt = $conn->prepare("DELETE FROM users WHERE username = ?");
    $stmt->bind_param("s", $username_to_delete);
    $stmt->execute();
    $stmt->close();
    
    // Redirect back to admin page
    header("Location: admin.php");
    exit();
}

// Check if view applications button is pressed
if(isset($_POST['show_applications'])){
    $show_applications = true;
} else {
    $show_applications = false;
}

// Fetch user data from the database
$sql_users = "SELECT * FROM users";
$result_users = $conn->query($sql_users);

// Fetch application data from the database if show_applications is true
if($show_applications){
    // Updated SQL query to order by points earned in descending order
    $sql_applications = "SELECT *, ";
    $sql_applications .= "IF(kcpe='yes', 1, 0) + IF(kcse='yes', 2, 0) + IF(certificate='yes', 4, 0) + ";
    $sql_applications .= "IF(diploma='yes', 6, 0) + IF(degree='yes', 8, 0) + IF(masters='yes', 10, 0) + ";
    $sql_applications .= "IF(phd='yes', 12, 0) + IF(nys_training='yes', 10, 0) + IF(volunteer_experience='yes', 10, 0) + ";
    $sql_applications .= "IF(duration='0-5', 1, IF(duration='6-10', 6, 12)) AS total_points ";
    $sql_applications .= "FROM applications ORDER BY total_points DESC";
    $result_applications = $conn->query($sql_applications);
}

// Count the total number of users and applications
$total_users = $result_users->num_rows;

// Count the total number of applications if show_applications is true
if($show_applications){
    $total_applications = $result_applications->num_rows;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-3">Admin Page</h2>
        <div>
            <form method="post">
                <button type="submit" class="btn btn-primary" name="show_users">View Users</button>
                <button type="submit" class="btn btn-primary" name="show_applications">View Applications</button>
            </form>
        </div>
        <?php if (!$show_applications) : ?>
            <p>Total Users: <?php echo $total_users; ?></p>
            <table class="table" id="usersTable">
                <!-- Table for users -->
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Full Name</th>
                        <th>Action</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result_users->num_rows > 0) {
                        $count = 1;
                        // Output data of each row
                        while($row = $result_users->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $count++ . "</td>";
                            echo "<td>" . $row["full_name"] . "</td>";
                            echo "<td><a href='userProfile.php?username=" . $row["username"] . "' class='btn btn-primary'>View Profile</a></td>";
                            // Add delete button with form for each user
                            echo "<td>
                                    <form action='' method='post'>
                                        <input type='hidden' name='delete_user' value='" . $row["username"] . "'>
                                        <button type='submit' class='btn btn-danger' onclick=\"return confirm('Are you sure you want to delete this user?')\">Delete</button>
                                    </form>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No users found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>Total Applicants: <?php echo $total_applications; ?></p>
            <table class="table" id="applicationsTable">
                <!-- Table for applications -->
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Full Name</th>
                        <th>Role Applied For</th>
                        <th>Points Over Total</th>
                        <th>Download CV</th>
                        <th>Action</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result_applications->num_rows > 0) {
                        $count = 1;
                        // Sum up the total possible points
                        $total_possible_points = 1 + 2 + 4 + 6 + 8 + 10 + 12 + 10 + 10 + 1 + 6 + 12;
                        // Output data of each row
                        while($row = $result_applications->fetch_assoc()) {
                            // Fetch the role applied for
                            $role_applied_for = $row['role_subcategory'];
                            // Calculate earned points for each applicant
                            $earned_points = 0;
                            if($row['kcpe'] == 'yes') $earned_points += 1;
                            if($row['kcse'] == 'yes') $earned_points += 2;
                            if($row['certificate'] == 'yes') $earned_points += 4;
                            if($row['diploma'] == 'yes') $earned_points += 6;
                            if($row['degree'] == 'yes') $earned_points += 8;
                            if($row['masters'] == 'yes') $earned_points += 10;
                            if($row['phd'] == 'yes') $earned_points += 12;
                            if($row['nys_training'] == 'yes') $earned_points += 10;
                            if($row['volunteer_experience'] == 'yes') $earned_points += 10;
                            if($row['duration'] == '0-5') $earned_points += 1;
                            if($row['duration'] == '6-10') $earned_points += 6;
                            if($row['duration'] == '10+') $earned_points += 12;
                            echo "<tr>";
                            echo "<td>" . $count++ . "</td>";
                            echo "<td>" . $row["full_name"] . "</td>";
                            echo "<td>" . $role_applied_for . "</td>"; // Display role applied for
                            echo "<td>" . $earned_points . " / " . $total_possible_points . "</td>";
                            // Add view CV link for each applicant
                            echo "<td><p><strong>CV:</strong><br><a href='uploads/" . $row['cv_path'] . "' download>Download CV</a></p></td>";
                            echo "<td><a href='applicationProfile.php?id=" . $row["id"] . "' class='btn btn-primary'>View Application</a></td>";
                            // Add delete button with form for each application
                            echo "<td>
                                    <form action='' method='post'>
                                        <input type='hidden' name='delete_application' value='" . $row["id"] . "'>
                                        <button type='submit' class='btn btn-danger' onclick=\"return confirm('Are you sure you want to delete this application?')\">Delete</button>
                                    </form>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No applications found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php
// Close the database connection
$conn->close();
?>
