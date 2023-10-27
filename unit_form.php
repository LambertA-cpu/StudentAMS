<?php
// Connect to the database
$db = new PDO('mysql:host=localhost;dbname=students', 'root', '');

// Start the session
session_start();

// Get all of the students from the database
$sql = "SELECT * FROM student";
$stmt = $db->prepare($sql);
$stmt->execute();
$students = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Student List</title>
</head>
<body>

<h1>Student List</h1>

<table border="1">
<tr>
<th>Student ID</th>
<th>Student Name</th>
<th>Email</th>
<TH>Function</TH>
</tr>

<?php foreach ($students as $student): ?>
<tr>
<td><?php echo $student['studentID']; ?></td>
<td><?php echo $student['student_name']; ?></td>
<td><?php echo $student['email']; ?></td>
<td><button><a href="register_unit.php?studentID=<?php echo $student['studentID']; ?>&email=<?php echo $student['email']; ?>">REGISTER UNIT</a></button></td>
</tr>
<?php endforeach; ?>

</table>

</body>
</html>
