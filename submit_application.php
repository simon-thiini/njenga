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

// Check if the file upload was successful
if ($_FILES['cv']['error'] === UPLOAD_ERR_OK) {
    // File was uploaded successfully, process the form
    // Retrieve form data
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $id_number = $_POST['id_number'];
    $county = $_POST['county'];
    $sub_county = $_POST['sub_county'];
    $date_of_birth = $_POST['date_of_birth'];
    $date_of_application = $_POST['date_of_application'];
    $education_level = $_POST['education_level'];
    $nys_training = $_POST['nys_training'];
    $volunteer_experience = $_POST['volunteer_experience'];
    $volunteer_details = isset($_POST['volunteer_details']) ? $_POST['volunteer_details'] : '';
    $duration = $_POST['duration'];
    $kcpe = $_POST['kcpe'];
    $kcse = $_POST['kcse'];
    $certificate = $_POST['certificate'];
    $diploma = $_POST['diploma'];
    $degree = $_POST['degree'];
    $masters = $_POST['masters'];
    $phd = $_POST['phd'];
    $role_category = $_POST['role_category'];
    $role_subcategory = $_POST['role_subcategory'];

    // Get the uploaded file information
    $file_name = $_FILES['cv']['name'];
    $file_tmp = $_FILES['cv']['tmp_name'];
    $file_size = $_FILES['cv']['size'];
    $file_error = $_FILES['cv']['error'];

    // Check file size
    if ($file_size > 5000000) { // 5MB limit
        echo "Sorry, your file is too large.";
        header("Location: index.html"); // Redirect to index.html
        exit();
    }

    // Upload file
    if (!move_uploaded_file($file_tmp, "uploads/" . $file_name)) {
        echo "Sorry, there was an error uploading your file.";
        header("Location: index.html"); // Redirect to index.html
        exit();
    }

    // Insert data into the database
    $stmt = $conn->prepare("INSERT INTO applications (full_name, email, phone_number, id_number, county, sub_county, date_of_birth, date_of_application, education_level, nys_training, volunteer_experience, volunteer_details, duration, kcpe, kcse, certificate, diploma, degree, masters, phd, role_category, role_subcategory, cv_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssssssssssssssss", $full_name, $email, $phone_number, $id_number, $county, $sub_county, $date_of_birth, $date_of_application, $education_level, $nys_training, $volunteer_experience, $volunteer_details, $duration, $kcpe, $kcse, $certificate, $diploma, $degree, $masters, $phd, $role_category, $role_subcategory, $file_name);
    $stmt->execute();
    $stmt->close();

    // Redirect to index.html after successful submission
    header("Location: index.html");
    exit();
} else {
    // Handle file upload errors
    echo "Sorry, your file was not uploaded.";
    header("Location: index.html"); // Redirect to index.html
    exit();
}

// Close the database connection
$conn->close();
?>
