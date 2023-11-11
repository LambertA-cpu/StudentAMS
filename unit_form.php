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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">
</head>
<body>

<div style="margin: 3% auto;">
    <h1 class="display-6">Student List</h1>
    <table border="1" class="table">
        <tr>
        <th scope="col">Student ID</th>
        <th scope="col">Student Name</th>
        <th scope="col">Email</th>
        <TH scope="col">Function</TH>
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
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>
