<?php
session_start();
include 'db.php'; // Ensure this points to your actual database connection file

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    exit('Access Denied');
}

$user_id = $_GET['id'] ?? '';

if ($user_id) {
    $delete_sql = "DELETE FROM users WHERE id = $user_id";
    if ($conn->query($delete_sql)) {
        echo "<script>alert('User deleted successfully'); window.location.href='manage_users.php';</script>";
    } else {
        exit('Error deleting user: ' . $conn->error);
    }
} else {
    exit('User ID not provided');
}
