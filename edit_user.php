<?php
session_start();
include 'db.php'; // Ensure this points to your actual database connection file

// Check for admin role in session
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    exit('Access Denied');
}

$error = '';
$success = '';
$user_id = $_GET['id'] ?? ''; // PHP 7 null coalesce operator

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process the form
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $role = $conn->real_escape_string($_POST['role']);

    // Update user data
    $update_sql = "UPDATE users SET username = '$username', email = '$email', role = '$role' WHERE id = $user_id";
    if ($conn->query($update_sql)) {
        $success = 'User updated successfully.';
    } else {
        $error = 'Error updating user: ' . $conn->error;
    }
}

// Fetch the user's current data
$sql = "SELECT username, email, role FROM users WHERE id = $user_id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    exit('User not found');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <!-- Bootstrap CSS (ensure you have the CSS included correctly) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Edit User</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    
    <form action="edit_user.php?id=<?= $user_id ?>" method="post">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <div class="form-group">
            <label for="role">Role</label>
            <select class="form-control" id="role" name="role">
                <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update User</button>
    </form>
</div>
</body>
</html>
