<?php
include 'db.php'; // Include database connection

$response = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    // Basic validation
    if (!empty($username) && !empty($email) && !empty($password)) {

        // Check if username or email already exists
        $userCheck = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $userCheck->bind_param("ss", $username, $email);
        $userCheck->execute();
        $userCheckResult = $userCheck->get_result();
        if ($userCheckResult->num_rows > 0) {
            $response = "Username or email already exists.";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare SQL query
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if ($stmt === false) {
                $response = "Error preparing statement: " . htmlspecialchars($conn->error);
            } else {
                $stmt->bind_param("sss", $username, $email, $hashed_password);

                // Execute query
                if ($stmt->execute()) {
                    $response = "New record created successfully";
                } else {
                    $response = "Error: " . htmlspecialchars($stmt->error);
                }
                $stmt->close(); // Make sure this is inside the else block
            }
        }
        $userCheck->close(); // Close this check after done using it
    } else {
        $response = "Please fill in all fields.";
    }
    $conn->close();
} else {
    $response = "Invalid request method.";
}

// Redirect back with the response message
header("Location: index.php?registrationStatus=" . urlencode($response));
exit();
?>
