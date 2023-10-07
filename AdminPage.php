<?php
require 'vendor/autoload.php';

use Kreait\Firebase\Factory;

// Check if the 'studentID' POST data is set
if (isset($_POST['studentID'])) {
    $studentID = $_POST['studentID'];
    $student_name = $_POST['student_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Connect to the MySQL database
    $db = new PDO('mysql:host=localhost;dbname=students', 'root', '');

    // Check if the student record exists
    $existingStudent = $db->prepare("SELECT * FROM student WHERE studentID = ?");
    $existingStudent->execute([$studentID]);

    if ($existingStudent->rowCount() > 0) {
        // Update the existing record
        $updateSql = "UPDATE student SET student_name = ?, email = ?, password = ? WHERE studentID = ?";
        $updateStmt = $db->prepare($updateSql);
        $updateStmt->execute([$student_name, $email, $password, $studentID]);
    } else {
        // Insert the student data into the MySQL database
        $insertSql = "INSERT INTO student (studentID, student_name, email, password) VALUES (?, ?, ?, ?)";
        $insertStmt = $db->prepare($insertSql);
        $insertStmt->execute([$studentID, $student_name, $email, $password]);
    }

    // Close the MySQL database connection
    $db = null;

    // Replace with your Firebase credentials and database URL
    $factory = (new Factory)
    ->withServiceAccount(__DIR__.'/google-service.json') // Use a relative path
    ->withDatabaseUri('https://campuspoints-e80de-default-rtdb.firebaseio.com/'); // Firebase Realtime Database URL

    $database = $factory->createDatabase();

    // Store the student data in the Firebase Realtime Database
    // $database = $firebase->getDatabase();
    $reference = $database->getReference('/users/' . $studentID);
    $reference->set([
        'studentID' => $studentID,
        'student_name' => $student_name,
        'email' => $email,
        'password' => $password,
    ]);

    // Redirect to the student list page
    header('Location: student_list.php');
} else {
    echo "Error: 'studentID' is not set in the POST data.";
}
?>
