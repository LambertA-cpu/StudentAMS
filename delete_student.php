<?php
require 'vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;

try {
    // Connect to the database
    $db = new PDO('mysql:host=localhost;dbname=students', 'root', '');

    // Set PDO to throw exceptions on errors
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the student ID from the URL
    $student_id = isset($_GET['studentID']) ? $_GET['studentID'] : null;

    if (!$student_id) {
        throw new Exception("No student ID provided.");
    }

    // Check if the user confirmed the deletion
    if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
        // Delete dependent records in the units table
        $sql = "DELETE FROM units WHERE studentID = :studentID";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':studentID', $student_id, PDO::PARAM_INT);
        $stmt->execute();

        // Delete the student data in the database
        $sql = "DELETE FROM student WHERE studentID = :studentID";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':studentID', $student_id, PDO::PARAM_INT);
        $stmt->execute();

        // Initialize Firebase
        $factory = (new Factory)
            ->withServiceAccount(__DIR__.'/google-service.json') // Use a relative path
            ->withDatabaseUri('https://campuspoints-e80de-default-rtdb.firebaseio.com/'); // Firebase Realtime Database URL

        // Remove the student data from Firebase
        $database = $factory->createDatabase();
        $reference = $database->getReference('/users/' . $uid);
        $reference->remove();

        // Redirect to the student list page
        header('Location: student_list.php');
        exit();
    }

    // Display a confirmation dialog
    echo '<script>';
    echo 'var result = confirm("Are you sure you want to delete student ' . $student_id . '?");';
    echo 'if (result) {';
    echo '   window.location.href = "delete_student.php?studentID=' . $student_id . '&confirm=yes";';
    echo '}';
    echo 'else {';
    echo '   window.location.href = "student_list.php";';
    echo '}';
    echo '</script>';
} catch (Exception $e) {
    // Handle exceptions (e.g., database errors, missing studentID)
    echo "Error: " . $e->getMessage();
}
?>
