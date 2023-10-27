<?php
require 'vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

// Load the service account JSON file to create a ServiceAccount object
$serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/google-service.json');

// Create a Firebase Factory using the ServiceAccount object
$firebase = (new Factory)
    ->withServiceAccount($serviceAccount)
    ->withDatabaseUri('https://campuspoints-e80de-default-rtdb.firebaseio.com/')
    ->create();

$database = $firebase->getDatabase();

// Verify the ID token sent from the client
$idToken = $_POST['idToken']; // Replace with the actual method of getting the ID token from your client

try {
    $verifiedIdToken = $firebase->getAuth()->verifyIdToken($idToken);

    // Get the user's UID from the verified ID token
    $uid = $verifiedIdToken->getClaim('sub');

    // Retrieve studentID and unitID from URL parameters
    $studentID = $_GET['studentID'];
    $unitID = $_GET['unitID'];

    // Define the path in Firebase where you want to store the data
    $firebasePath = '/users/' . $uid . '/students/' . $studentID . '/units/' . $unitID;

    // Retrieve other unit data from your form
    $semester = $_POST['semester'];
    $unitName = $_POST['unit_name'];
    $unitGrade = $_POST['grades'];
    $unitAttendance = $_POST['attendance'];

    // Your data to be added to Firebase
    $unitData = [
        'semester' => $semester,
        'unitID' => $unitID,
        'unit_name' => $unitName,
        'grades' => $unitGrade,
        'attendance' => $unitAttendance,
    ];

    // Store the unit data in Firebase
    $reference = $database->getReference($firebasePath);
    $reference->set($unitData);

    echo "Unit data added to Firebase successfully!";
} catch (\Exception $e) {
    // Handle authentication error or other exceptions
    echo 'An error occurred: ' . $e->getMessage();
}
?>
