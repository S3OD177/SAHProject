<?php
session_start();
include 'db.php';

if(isset($_SESSION['user_id']) && isset($_POST['activity_id'])) {
    $userId = $_SESSION['user_id'];
    $activityId = $conn->real_escape_string($_POST['activity_id']);

    // Check if the user is already enrolled
    $check = $conn->prepare("SELECT id FROM enrollments WHERE user_id = ? AND activity_id = ?");
    $check->bind_param("ii", $userId, $activityId);
    $check->execute();
    $result = $check->get_result();
    if($result->num_rows == 0) {
        // Not already enrolled, proceed with enrollment
        $insert = $conn->prepare("INSERT INTO enrollments (user_id, activity_id) VALUES (?, ?)");
        $insert->bind_param("ii", $userId, $activityId);
        if($insert->execute()) {
            echo 'success';
        } else {
            echo 'error';
        }
    } else {
        echo 'already_enrolled';
    }
} else {
    echo 'unauthenticated';
}
?>

<script>
$(document).ready(function() {
    $('.enrollBtn').click(function() {
        var activityId = $(this).data('activity-id');
        var button = $(this); // Reference to the button for later use
        
        $.ajax({
            url: 'enroll_activity.php',
            type: 'POST',
            data: { 'activity_id': activityId },
            success: function(response) {
                // Handle response here. For example, disable the button on successful enrollment
                if(response === 'success') {
                    button.prop('disabled', true).text('Enrolled');
                } else {
                    alert('Failed to enroll. Please try again.');
                }
            }
        });
    });
});
</script>