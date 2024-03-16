<?php
session_start();
include 'db.php'; // Your database connection file

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    exit('Access Denied');
}

$activity_id = $_GET['id'] ?? '';

if ($activity_id) {
    $delete_sql = "DELETE FROM activities WHERE id = $activity_id";
    if ($conn->query($delete_sql)) {
        echo "<script>alert('Activity deleted successfully'); window.location.href='manage_activities.php';</script>";
    } else {
        exit('Error deleting activity: ' . $conn->error);
    }
} else {
    exit('Activity ID not provided');
}
