<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body style="text-align: center;">

<h1 class="display-6">Edit Student</h1>

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
        <form action="edit_student.php?studentID=' . $studentID . '" method="post" style="margin: 10% auto; width: 500px; height:400px;">
            <div class="mb-3">
                <input type="hidden" name="studentID" value="' . $student['studentID'] . '">
            </div>
            <div class="mb-3">
                <label for="student_name">Student Name:</label>
                <input type="text" name="student_name" value="' . $student['student_name'] . '" placeholder="Student Name">
            </div>
            <div class="mb-3">
                <label for="email">Email address:</label>
                <input type="email" name="email" value="' . $student['email'] . '" placeholder="Email">
            <div class="mb-3">
                <label for="password">Password:</label>
                <input type="password" name="password" value="' . $student['password'] . '" placeholder="Password">
            </div>
            <div class="d-grid gap-2">
                <button class="btn btn-primary" type="submit">Update Student</button>
            </div>
        </form>';
        
    } else {
        echo 'Student not found.';
    }
} else {
    echo 'No studentID provided in the URL.';
}

?><br><a href="delete_student.php?studentID=<?php echo $student['studentID']; ?>">

<div class="d-grid gap-2" style="width: 500px; margin:auto">
    <button class="btn btn-danger" type="button">Delete Student</button>
</div>
</a>
<!-- scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>
