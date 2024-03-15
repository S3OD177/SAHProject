<?php
// Include config file
require_once "config.php";
 
// Initialize variables
$activities = "";
 
// Retrieve all activities from the database
$sql = "SELECT * FROM activities";
$result = mysqli_query($link, $sql);

if (mysqli_num_rows($result) > 0) {
    // Output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $activities .= "<p>" . $row["activity_name"] . "</p>";
    }
} else {
    $activities = "No activities found";
}

// Close connection
mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
</head>
<body>
    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
    </div>
    <p>
        <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
    </p>
    <div class="activities">
        <?php echo $activities; ?>
    </div>
</body>
</html>