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

    // Get the student data from the form
    $student_name = $_POST['student_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Update the student data in the database
    $sql = "UPDATE student SET student_name = ?, email = ?, password = ? WHERE studentID = ?";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(1, $student_name);
    $stmt->bindParam(2, $email);
    $stmt->bindParam(3, $password);
    $stmt->bindParam(4, $student_id);
    $stmt->execute();

    // Check if any rows were affected
    if (!$stmt) {
        throw new Exception("No student found with ID: " . $student_id);
    }

    // Initialize Firebase
    $factory = (new Factory)
        ->withServiceAccount(__DIR__.'/google-service.json') // Use a relative path
        ->withDatabaseUri('https://campuspoints-e80de-default-rtdb.firebaseio.com/'); // Firebase Realtime Database URL

    // Update the student data in Firebase
    $database = $factory->createDatabase();
    $reference = $database->getReference('/users/' . $student_id);
    $reference->update([
        'student_name' => $student_name,
        'email' => $email,
        'password' => $password,
    ]);

    // Initialize Firebase Authentication
    $auth = $factory->createAuth();

    // Check if the student already exists in Firebase
    try {
        $existingUser = $auth->getUserByEmail($email);
        // User exists, update their information if needed
        $uid = $existingUser->uid;
        $auth->updateUser($uid, [
            'displayName' => $student_name,
        ]);
    } catch (Kreait\Firebase\Exception\Auth\UserNotFound $e) {
        // User doesn't exist, create a new Firebase user
        $newUser = $auth->createUser([
            'email' => $email,
            'password' => $password,
            'displayName' => $student_name,
        ]);
    }

    // Redirect to the student list page
    header('Location: student_list.php');
    exit();
} catch (Exception $e) {
    // Handle exceptions (e.g., database errors, missing studentID)
    echo "Error: " . $e->getMessage();
}
?>
