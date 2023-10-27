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
</head>
<body>
    <h1>Student Academic</h1>

    <!-- Add a form to submit new academic data -->
    <form method="POST">
        <label for="subject">Subject:</label>
        <input type="text" name="subject" required><br>

        <label for="grade">Grade:</label>
        <input type="text" name="grade" required><br>

        <label for="attendance">Attendance:</label>
        <input type="text" name="attendance" required><br>

        <input type="submit" value="Add Data">
    </form>

    <table border="1">
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
</body>
</html>
