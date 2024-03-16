<?php
session_start();
include 'db.php'; // Make sure this points to your actual database connection file

// Security check: Ensure the user is logged in as an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    exit('Access Denied');
}

// Initialize variables
$error = '';
$success = '';
$activity_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process form submission
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $date = $conn->real_escape_string($_POST['date']);
    $location = $conn->real_escape_string($_POST['location']);
    $max_students = (int)$_POST['max_students'];

    // Assuming you're not updating the image in this example
    // Update the activity in the database
    $sql = "UPDATE activities SET title = '$title', description = '$description', date = '$date', location = '$location', max_students = $max_students WHERE id = $activity_id";

    if ($conn->query($sql) === TRUE) {
        $success = "Activity updated successfully.";
    } else {
        $error = "Error updating record: " . $conn->error;
    }
}

// Fetch the current activity data
if (!$activity_id) {
    $error = 'Activity ID not specified.';
} else {
    $sql = "SELECT id, title, description, date, location, max_students FROM activities WHERE id = $activity_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $activity = $result->fetch_assoc();
    } else {
        $error = "No activity found with ID $activity_id";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Activity</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Edit Activity</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    
    <?php if (!empty($activity)): ?>
    <form action="edit_activity.php?id=<?= $activity_id ?>" method="post">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($activity['title']) ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea class="form-control" id="description" name="description" required><?= htmlspecialchars($activity['description']) ?></textarea>
        </div>
        <div class="form-group">
            <label for="date">Date:</label>
            <input type="date" class="form-control" id="date" name="date" value="<?= htmlspecialchars($activity['date']) ?>" required>
        </div>
        <div class="form-group">
            <label for="location">Location:</label>
            <input type="text" class="form-control" id="location" name="location" value="<?= htmlspecialchars($activity['location']) ?>" required>
        </div>
        <div class="form-group">
            <label for="max_students">Max Students:</label>
            <input type="number" class="form-control" id="max_students" name="max_students" value="<?= htmlspecialchars($activity['max_students']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Activity</button>
    </form>
    <?php endif; ?>
</div>
</body>
</html>
