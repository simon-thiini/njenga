<?php 
    // Connect to MySQL server
    $link = mysqli_connect('localhost', 'root', '', 'resumeranker');

    // Check connection
    if (!$link) {
        die('Failed to connect to server: ' . mysqli_connect_error());
    }

    // Select database
    $db = mysqli_select_db($link, 'resumeranker');
    if (!$db) {
        die("Unable to select database");
    }
?>
