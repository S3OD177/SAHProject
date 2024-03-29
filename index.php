<?php
session_start();
include 'db.php'; // Ensure this points to your actual database connection file

// Fetch activities from database
$activities = [];
$query = "SELECT id, title, description, date, location FROM activities ORDER BY date DESC";
if ($result = $conn->query($query)) {
    while ($row = $result->fetch_assoc()) {
        $activities[] = $row;
    }
    $result->free();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Activity Hub</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/custom.css" rel="stylesheet">
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <a class="navbar-brand" href="#">Student Activity Hub</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="#">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Features</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Contact</a>
      </li>
      <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item">
              <a class="nav-link" href="logout.php">Logout</a>
          </li>
      <?php else: ?>
          <li class="nav-item">
              <a class="nav-link" href="#" data-toggle="modal" data-target="#loginModal">Login</a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="#" data-toggle="modal" data-target="#registerModal">Register</a>
          </li>
      <?php endif; ?>
    </ul>
  </div>
</nav>

<!-- Main Content -->
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h1>Welcome to the Student Activity Hub</h1>
            <p>This is your one-stop destination for all student activities, events, and resources.</p>
        </div>
    </div>
</div>
<!-- Activities Section -->
<div class="container mt-4">
    <h2>Upcoming Activities</h2>
    <div class="row">
        <?php foreach ($activities as $activity): ?>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($activity['title']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($activity['description']) ?></p>
                        <p class="card-text"><small class="text-muted">Date: <?= htmlspecialchars($activity['date']) ?></small></p>
                        <p class="card-text"><small class="text-muted">Location: <?= htmlspecialchars($activity['location']) ?></small></p>
                        <?php if (new DateTime() < new DateTime($activity['date'])): ?>
                            <button class="btn btn-primary enrollBtn" data-activity-id="<?= $activity['id'] ?>">Enroll</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($activities)): ?>
            <p>No activities found.</p>
        <?php endif; ?>
    </div>
</div>
<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="loginModalLabel">Login</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="login.php" method="post">
          <div class="form-group">
            <label for="loginEmail">Email address</label>
            <input type="email" class="form-control" id="loginEmail" name="email" required>
          </div>
          <div class="form-group">
            <label for="loginPassword">Password</label>
            <input type="password" class="form-control" id="loginPassword" name="password" required>
          </div>
          <button type="submit" class="btn btn-primary">Login</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Register Modal -->
<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerModalLabel">Register</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form action="register.php" method="post">
                    <div class="form-group">
                        <label for="registerUsername">Username</label>
                        <input type="text" class="form-control" id="registerUsername" name="username" required>
                        <small id="usernameHelp" class="form-text"></small>
                    </div>
                    <div class="form-group">
                        <label for="registerEmail">Email address</label>
                        <input type="email" class="form-control" id="registerEmail" name="email" required>
                        <small id="emailHelp" class="form-text"></small>
                    </div>
                    <div class="form-group">
                        <label for="registerPassword">Password</label>
                        <input type="password" class="form-control" id="registerPassword" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-success">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
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
                response = response.trim();
                if(response === 'success') {
                    button.prop('disabled', true).text('Enrolled');
                } else if (response === 'already_enrolled') {
                    button.prop('disabled', true).text('Already Enrolled');
                } else {
                    alert('Failed to enroll. Please try again.');
                }
            }
        });
    });

    // Optionally, check enrollments when the page loads
    $('.enrollBtn').each(function() {
        var button = $(this);
        var activityId = button.data('activity-id');

        $.ajax({
            url: 'check_enrollment.php', // You'll need to create this PHP file
            type: 'POST',
            data: { 'activity_id': activityId },
            success: function(response) {
                if (response.trim() === 'already_enrolled') {
                    button.prop('disabled', true).text('Already Enrolled');
                }
            }
        });
    });
});
</script>



</body>
</html>