<?php
session_start();
include 'db.php';

if(isset($_SESSION['user_id']) && isset($_POST['activity_id'])) {
    $userId = $_SESSION['user_id'];
    $activityId = $conn->real_escape_string($_POST['activity_id']);

    $stmt = $conn->prepare("SELECT id FROM enrollments WHERE user_id = ? AND activity_id = ?");
    $stmt->bind_param("ii", $userId, $activityId);
    $stmt->execute();
    $result = $stmt->get_result();

    echo $result->num_rows > 0 ? 'already_enrolled' : 'not_enrolled';
    $stmt->close();
} else {
    echo 'unauthenticated';
}
?>
