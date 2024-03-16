
<?php
$servername = "localhost";
$username = "root"; // Default username for localhost
$password = ""; // Default password for localhost (empty)
$dbname = "student_activity_hub";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
