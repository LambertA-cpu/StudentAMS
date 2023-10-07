<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
</head>
<body>

<h1>Edit Student</h1>

<?php
// Check if studentID is set in the URL
if (isset($_GET['studentID']) && !empty($_GET['studentID'])) {
    // Connect to the database
    $db = new PDO('mysql:host=localhost;dbname=students', 'root', '');

    // Get the studentID from the URL
    $studentID = $_GET['studentID'];

    // Query the database to fetch the student's information
    $sql = "SELECT * FROM student WHERE studentID = ?";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(1, $studentID);
    $stmt->execute();
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the student exists before displaying the form
    if ($student) {
        // Populate the form fields with the fetched data and set the form action to edit_student.php
        echo '
        <form action="edit_student.php?studentID=' . $studentID . '" method="post">
            <input type="hidden" name="studentID" value="' . $student['studentID'] . '">
            <input type="text" name="student_name" value="' . $student['student_name'] . '" placeholder="Student Name">
            <input type="email" name="email" value="' . $student['email'] . '" placeholder="Email">
            <input type="password" name="password" value="' . $student['password'] . '" placeholder="Password">
            <button type="submit">Update Student</button>
        </form>';
    } else {
        echo 'Student not found.';
    }
} else {
    echo 'No studentID provided in the URL.';
}
?>

</body>
</html>
