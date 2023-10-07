<?php
// Connect to the database
$db = new PDO('mysql:host=localhost;dbname=students', 'root', '');

// Get the username and password from the form
$admin_id = $_POST['admin_id'];
$password = $_POST['password'];

// Check the username and password against the database
$sql = "SELECT * FROM admins WHERE admin_id = ? AND password = ?";
$stmt = $db->prepare($sql);
$stmt->bindParam(1, $admin_id);
$stmt->bindParam(2, $password);
$stmt->execute();
$admin = $stmt->fetch();

// Start the session
session_start();

// If the admin does not exist, redirect to the login page with an error message
if (!$admin) {
    $message = "Incorrect admin_id or password.";
    $_SESSION['login_error'] = $message;
    header('Location: login_form.html');
    exit(); // Terminate script to prevent further execution
}

// Set the session variables
$_SESSION['admin_id'] = $admin_id;
// $_SESSION['admin_username'] = $admin['username'];

// Redirect to the admin dashboard
header('Location: admin_dashboard.php');
exit(); // Terminate script to prevent further execution
?>
