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
<title>Admin Dashboard</title>
    
    <head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <script src="bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
    <h1>Admin Dashboard</h1>

    <p>Welcome, <?php echo $_SESSION['admin_id']; ?>!</p>

    <div id="mySidenav" class="sidenav">
        <ul>
            <li><a href="student_form.html">Add Student</a></li>
            <li><a href="student_list.php">View Students</a></li>
            <li><a href="unit_form.php">Register unit</a></li>
            <li><a href="student_units.php">View Students Units</a></li>
        </ul>
    </div>
</body>
</html>
