<?php
session_start();
include 'db.php';

header('Content-Type: text/plain'); // Set Content-Type header at the beginning

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
            // You could log the error somewhere instead of echoing it for security
            //echo 'error';
            error_log('Insertion error: ' . $insert->error); // Log error to the server's error log
            echo 'error';
        }
    } else {
        echo 'already_enrolled';
    }
    $insert->close(); // Close the statemen
    $check->close(); // Close the statement
} else {
    echo 'unauthenticated';
}
?>


<!-- Include jQuery first -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script>
$(document).ready(function() {
    $('.enrollBtn').click(function() {
        var activityId = $(this).data('activity-id');
        var button = $(this);

        $.ajax({
            url: 'enroll_activity.php',
            type: 'POST',
            data: { 'activity_id': activityId },
            success: function(response) {
                response = response.trim();
                if(response === 'success') {
                    button.prop('disabled', true).text('Enrolled');
                } else {
                    alert(response); // Changed to output the actual response for debugging
                }
            }
        });
    });
});
</script>

