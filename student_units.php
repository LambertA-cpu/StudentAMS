<?php
// Initialize Firebase Admin SDK
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

// ...

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $subject = $_POST['subject'];
    $grade = $_POST['grade'];
    $attendance = $_POST['attendance'];

    // Assuming you have already authenticated the user and have their UID
    $uid = $_SESSION['uid']; // Replace with your authentication method

    // Create a reference to the Firebase Realtime Database
    $firebase = (new Factory)->withServiceAccount($serviceAccount);
    $database = $firebase->createDatabase();
    $dataRef = $database->getReference("users/$uid/academic_data");

    // Push the new academic data to the user's data in Firebase
    $newData = [
        'subject' => $subject,
        'grade' => $grade,
        'attendance' => $attendance,
    ];
    $dataRef->push($newData);

    // Perform SQL database insertion if needed
    // $sql = "INSERT INTO academic_data (subject, grade, attendance) VALUES (:subject, :grade, :attendance)";
    // $stmt = $db->prepare($sql);
    // $stmt->bindParam(':subject', $subject);
    // $stmt->bindParam(':grade', $grade);
    // $stmt->bindParam(':attendance', $attendance);
    // $stmt->execute();

    // Redirect to the same page to avoid resubmission
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Get all of the students from the Firebase Realtime Database
// Assuming you have already authenticated the user and have their UID
$uid = $_SESSION['uid']; // Replace with your authentication method
$firebase = (new Factory)->withServiceAccount($serviceAccount);
$database = $firebase->createDatabase();
$dataRef = $database->getReference("users/$uid/academic_data");
$students = $dataRef->getValue();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Student List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    

    <!-- Add a form to submit new academic data -->
    <form method="POST" style="margin:auto; width: 500px; height:400px;">
        <h1 class="display-6">Add Academic Data</h1>
        <div class="mb-3">
            <label for="subject">Subject:</label>
            <input type="text" name="subject" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="grade">Grade:</label>
            <input type="text" name="grade" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="attendance">Attendance:</label>
            <input type="text" name="attendance" class="form-control" required>
        </div>
        <div class="d-grid gap-2">
            <input type="submit" class="btn btn-primary" value="Add Data">
        </div>
    </form>

    <table border="1" class="table">
        <tr>
            <th>Student ID</th>
            <th>Subject</th>
            <th>Grade</th>
            <th>Attendance</th>
            <th>Function</th>
        </tr>

        <?php if ($students): ?>
            <?php foreach ($students as $key => $student): ?>
                <tr>
                    <td><?php echo $key; ?></td>
                    <td><?php echo $student['subject']; ?></td>
                    <td><?php echo $student['grade']; ?></td>
                    <td><?php echo $student['attendance']; ?></td>
                    <td><button><a href="edit_studentForm.php?studentID=<?php echo $key; ?>">EDIT</a></button></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No academic data found.</td>
            </tr>
        <?php endif; ?>

    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>
