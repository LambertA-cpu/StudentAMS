<?php

// Connect to the database
$db = new PDO('mysql:host=localhost;dbname=students', 'root', '');

// Get all of the students from the database
$sql = "SELECT * FROM academic_data";
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

<h1>Student Academic</h1>

<table border="1">
<tr>
<th>Student ID</th>
<th>Subject</th>
<th>Grade</th>
<th>Attendance</th>
<TH>Function</TH>
</tr>

<?php foreach ($students as $student): ?>
<tr>
<td><?php echo $student['studentID']; ?></td>
<td><?php echo $student['subject']; ?></td>
<td><?php echo $student['grade']; ?></td>
<td><?php echo $student['attendance']; ?></td>
<td><button><a href="edit_studentForm.php?studentID=<?php echo $student['studentID']; ?>">EDIT</a></button></td>
<!-- <td><a href="delete-student.php?student_id=<?php echo $student['studentID']; ?>">Delete</a></td> -->
</tr>
<?php endforeach; ?>

</table>

</body>
</html>