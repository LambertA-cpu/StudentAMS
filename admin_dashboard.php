<!-- <?php

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['admin_id'])) {
    // header('Location: login.php');
    // exit(); // Terminate script to prevent further execution
}

// Get the admin data from the database
// $db = new PDO('mysql:host=localhost;dbname=students', 'root', '');
// $sql = "SELECT * FROM admins WHERE id = ?";
// $stmt = $db->prepare($sql);
// $stmt->bindParam(1, $admin_id);
// $stmt->execute();
// $admin = $stmt->fetch();

?> -->

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
</head>
<body>

<h1>Admin Dashboard</h1>

<p>Welcome, <?php echo $_SESSION['admin_id']; ?>!</p>

<ul>
<li><a href="student_form.html">Add Student</a></li>
<li><a href="student_list.php">View Students</a></li>
<li><a href="unit_form.php">Register unit</a></li>
<li><a href="student_units.php">View Students Units</a></li>
</ul>

</body>
</html>
