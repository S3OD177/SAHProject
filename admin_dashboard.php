<?php
// At the top of your file
session_start();
include 'db.php'; // Adjust this to your database connection file

// Access control
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    exit('Access Denied');
}

// Fetch users from the database
$usersQuery = "SELECT id, username, role FROM users";
$result = $conn->query($usersQuery);
?>

<h2>Edit User Roles</h2>

<?php if ($result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td>
                        <form action="edit_role.php" method="post">
                            <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                            <select name="role" onchange="this.form.submit()">
                                <option value="user" <?php if ($row['role'] == 'user') echo 'selected'; ?>>User</option>
                                <option value="admin" <?php if ($row['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                            </select>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No users found.</p>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1>Admin Dashboard</h1>
    <nav>
        <ul class="nav">
            <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="manage_users.php">Manage Users</a></li>
            <li class="nav-item"><a class="nav-link" href="manage_activities.php">Manage Activities</a></li>
            <li class="nav-item"><a class="nav-link" href="settings.php">Settings</a></li>
        </ul>
    </nav>
    
    <!-- Dashboard Content -->
    <p>Welcome to the admin panel!</p>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
