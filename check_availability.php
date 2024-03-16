<?php
include 'db.php'; // Make sure this points to your actual database connection file

if (!empty($_POST['username'])) {
    $username = $_POST['username'];
    $query = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();
    echo $result->num_rows > 0 ? "taken" : "available";
} elseif (!empty($_POST['email'])) {
    $email = $_POST['email'];
    $query = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();
    echo $result->num_rows > 0 ? "taken" : "available";
} else {
    echo "error"; // Incorrect POST request
}
?>
