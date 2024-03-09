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

// Check if an application ID is provided
if(isset($_GET['id'])){
    // Get the application ID
    $application_id = $_GET['id'];
    
    // Prepare and execute query to fetch application details
    $stmt = $conn->prepare("SELECT * FROM applications WHERE id = ?");
    $stmt->bind_param("i", $application_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0){
        // Fetch application details
        $application = $result->fetch_assoc();

        // Calculate points
        $points = 0;
        if ($application['kcpe'] == 'yes') $points += 1;
        if ($application['kcse'] == 'yes') $points += 2;
        if ($application['certificate'] == 'yes') $points += 4;
        if ($application['diploma'] == 'yes') $points += 6;
        if ($application['degree'] == 'yes') $points += 8;
        if ($application['masters'] == 'yes') $points += 10;
        if ($application['phd'] == 'yes') $points += 12;
        if ($application['nys_training'] == 'Yes') $points += 10;
        if ($application['volunteer_experience'] == 'Yes') $points += 10;

        // Calculate points based on duration
        $duration_points = 0;
        switch ($application['duration']) {
            case '0-5':
                $duration_points = 1;
                break;
            case '6-10':
                $duration_points = 6;
                break;
            case '10+':
                $duration_points = 12;
                break;
            default:
                $duration_points = 0;
        }
        $points += $duration_points;

        // Output application details within a centered Bootstrap card
        echo '<div class="card">';
        echo '<div class="card-body text-center">';
        echo '<h2 class="card-title">Application Details</h2>';
        echo "<div class='row'>";
        echo "<div class='col'>";
        echo "<p><strong>Full Name:</strong><br>" . $application['full_name'] . "</p>";
        echo "<hr>";
        echo "<p><strong>Email:</strong><br>" . $application['email'] . "</p>";
        echo "<hr>";
        echo "<p><strong>Phone Number:</strong><br>" . $application['phone_number'] . "</p>";
        echo "<hr>";
        echo "<p><strong>ID Number:</strong><br>" . $application['id_number'] . "</p>";
        echo "<hr>";
        echo "<p><strong>County:</strong><br>" . $application['county'] . "</p>";
        echo "<hr>";
        echo "<p><strong>Subcounty:</strong><br>" . $application['sub_county'] . "</p>";
        echo "<hr>";
        echo "<p><strong>Date of Birth:</strong><br>" . $application['date_of_birth'] . "</p>";
        echo "<hr>";
        echo "<p><strong>Date of Application:</strong><br>" . $application['date_of_application'] . "</p>";
        echo "<hr>";
        echo "<p><strong>Education Level:</strong><br>" . $application['education_level'] . " (Points: " . ($application['kcpe'] == 'yes' ? '1' : '0') . " KCPE, " . ($application['kcse'] == 'yes' ? '2' : '0') . " KCSE, " . ($application['certificate'] == 'yes' ? '4' : '0') . " Certificate, " . ($application['diploma'] == 'yes' ? '6' : '0') . " Diploma, " . ($application['degree'] == 'yes' ? '8' : '0') . " Degree, " . ($application['masters'] == 'yes' ? '10' : '0') . " Masters, " . ($application['phd'] == 'yes' ? '12' : '0') . " PhD)</p>";
        echo "<hr>";
        echo "<p><strong>NYC Training:</strong><br>" . $application['nys_training'] . " (Points: " . ($application['nys_training'] == 'Yes' ? '10' : '0') . ")</p>";
        echo "<hr>";
        echo "<p><strong>Volunteer Experience:</strong><br>" . $application['volunteer_experience'] . " (Points: " . ($application['volunteer_experience'] == 'Yes' ? '10' : '0') . ")</p>";
        echo "<hr>";
        if($application['volunteer_experience'] == 'Yes') {
            echo "<p><strong>Details of Volunteer Experience:</strong><br>" . $application['volunteer_details'] . "</p>";
            echo "<hr>";
        }
        echo "<p><strong>Preferred Duration of Service:</strong><br>" . $application['duration'] . " (Points: " . $duration_points . ")</p>";
        echo "<hr>";
        echo "<p><strong>Do you have the certificate in KCPE?:</strong><br>" . $application['kcpe'] . "</p>";
        echo "<hr>";
        echo "<p><strong>Do you have the certificate in KCSE?:</strong><br>" . $application['kcse'] . "</p>";
        echo "<hr>";
        echo "<p><strong>Do you have a certificate?:</strong><br>" . $application['certificate'] . "</p>";
        echo "<hr>";
        echo "<p><strong>Do you have a diploma?:</strong><br>" . $application['diploma'] . "</p>";
        echo "<hr>";
        echo "<p><strong>Do you have a degree?:</strong><br>" . $application['degree'] . "</p>";
        echo "<hr>";
        echo "<p><strong>Do you have a masters degree?:</strong><br>" . $application['masters'] . "</p>";
        echo "<hr>";
        echo "<p><strong>Do you have a PhD?:</strong><br>" . $application['phd'] . "</p>";
        echo "<hr>";
        echo '</div>'; // End of col
        echo "<div class='col'>";
        echo "<h5 class='card-title'>Role Selection</h5>";
        echo "<p><strong>Role Category:</strong><br>" . $application['role_category'] . "</p>";
        echo "<hr>";
        echo "<p><strong>Role Subcategory:</strong><br>" . $application['role_subcategory'] . "</p>";
        echo "<hr>";
        echo "<p><strong>Qualification Requirements:</strong><br>" . $application['qualification_requirements'] . "</p>";
        echo "<hr>";
        echo "<p><strong>CV:</strong><br><a href='uploads/" . $application['cv_path'] . "' download>Download CV</a></p>";
        echo "<hr>";
        echo "<p><strong>Total Points:</strong> " . $points . " out of 82</p>";
        echo "<hr>";
        echo '</div>'; // End of col
        echo "</div>"; // End of row
        echo "</div>"; // End of card-body
        echo "</div>"; // End of card
    } else {
        echo "Application not found.";
    }
    $stmt->close();
} else {
    echo "Application ID not provided.";
}
// Close the database connection
$conn->close();
?>
