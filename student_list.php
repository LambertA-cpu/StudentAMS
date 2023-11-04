<?php
// Include the Firebase Admin SDK and other necessary libraries
require 'vendor/autoload.php'; 

// Initialize Firebase Admin SDK
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

// ...

// Define the createFirebaseUser function
function createFirebaseUser($auth, $student) {
    // Get the student's email address and password.
    $email = $student['email'];
    $password = $student['password'];

    // Initialize Firebase user variable
    $firebaseUser = null;

    try {
        // Check if the student's email address is already registered with Firebase.
        $user = $auth->getUserByEmail($email);
        
        if ($user) {
            // User already exists
            echo 'User with email ' . $email . ' already exists in Firebase.';
        } else {
            // Create a new Firebase user with the student's email address and password.
            $firebaseUser = $auth->createUser([
                'email' => $email,
                'password' => $password,
                'displayName' => $student['student_name'],
            ]);

            echo 'Firebase user created with email: ' . $email;
        }
    } catch (Kreait\Firebase\Exception\Auth\UserNotFound $e) {
        // Handle any exceptions here
        echo 'Error creating Firebase user: ' . $e->getMessage();
    }

    return $firebaseUser;
}

// ...

$serviceAccount = ServiceAccount::fromValue([
    "type"=> "service_account",
    "project_id"=> "campuspoints-e80de",
    "private_key_id"=> "23584686fbacf452b962252d8ec36fdb5b33246f",
    "private_key"=> "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDEqFrBHp8+O7GH\nclbqJToYQ0ToCRz/79csiTsz9e4U+/La/LRtb8dPMReVcgP0G99/qZTvyZ7ETzAs\nZeOJt3vY7TK3sDZ7sc6GxBCM6vr6Vc+RWuLQEfZZ/Blq/ciEeq4eNNaXLA6nThMk\nzKKHtZ0BX1DzcjV6NQf5JlSeKtwU14mGFr1uGk0kMajgyqEAsG3tYbCnQ2I+WYA7\nC1F44m3/ZTRl30QfQ15kbdXmg7Ug6sSecz2Wwx9CKPzHos+xtgWNNmSJRaxQ139c\ni0GGN7cpCyKPafkUjTNh0IdMInTfoVSJkEgwXVPP12P2emUnhKtbOeyREy7pKPS+\nMo38GbwRAgMBAAECggEARAFP+O5k9Plhh8wzYB7lexRwFKlqHNtMlnsSPQKzhRAn\nSDERF7MKwkjgte5KjbTB0fzLoyweuhYEO6Y0TCBmNHq6Cilmfdnb6GCOtJxhlAXu\nlH5QZtF/VljGVWhTWfSul4WAG6DBpHtsRVJ6deGI3LugW79H3O3PxwnJQzThDFdt\nOSwox4miNCBJvKIofuX+UKJXIUeqQ2nHSYm6PIPHbYMyV9TVjVe2zmH2jpE0tgpM\nljTmE+1vnMyQX3qmTga+3avYwA5wRgrUe6GoyVNYz8m4oqfdImFYmssKpVTpRkd3\n5hHAraEifw/y3h2n4DDppOcnTB6dEAnd2LqLgmRf7QKBgQDrttk3ciwLA+9vPpNJ\nsIHNN+Cu64dsdItk7Gz85TLcWO2oJvlv1cvHR447/2bwdCqSZrNu3PSeF3AuCL4a\ny/fMqA3S8bFa3CI+hNXSFtmmWyPzwcmwbj6Q97U+PlQ1Qsp5bAd0Jp0PFTGRt/3c\nI4BMjorUM5+RVu4wKLMcbh4DIwKBgQDVlQcq9v1dKPCRe6zz9vj4OQ92bLuR5bpr\n1RLt/uU8k8PgTGrBbprvAWScku0nF/Xg8vOS2RROsaZkrR/mWtBMBpkD3OxMb2AK\nw4KNux3V2WhY4FlhqtqgEDeqyEAPQKfuONc5mHo4jMIyjXY4i4xXagE/kfesofRO\nGvpC/lKhOwKBgGE5km8kULZacTBZhzcl7uRO4AZGcB10FOsT+gB2KXxp0d1B9jEC\n5oLUKP7mYfdccxLf+bMIwH1U/Zh/UqqK5zMrPkh8GMN5eUaAVBHMCwprHXE1xZNY\nZcY15jQ+UU2Gty1OOeTW6IGIJGIOmk9q9UieovTUNkUkrip/HyHi6c4xAoGADNIb\nUgrtfpAckL8ZzLNAkrXqJbO1NrbRj/OEZWDtpctA8M/lgKMBDePJhFSVg8k+azhq\nQqBhRbAISvbReWRuKIIk4UR7ssLkwwClb5iR2+pnBx7AfspgwUb38HqAzemVqwki\nckSi4Y4hgiNXu2E1FXI5jPVYJra6EY4xA6B8mU0CgYEA2fxyODXmhvZ2TekNByA8\nIXNQN+4uDZ265Hyw213LwixJnxt4CQGfo5xi8zoIFMtXiAsW2lC18K9sJBtzYIO2\nxMYCqsAeU6sCjOsnzhJJk3C1thobSxHqNxrhvwPILYowlT1VtoaiOEQmkupWh8g5\nX2YlsKFP2PjZV9ulJ878sgQ=\n-----END PRIVATE KEY-----\n",
    "client_email"=> "firebase-adminsdk-hhgur@campuspoints-e80de.iam.gserviceaccount.com",
    "client_id"=> "106245423444604729579",
    "auth_uri"=> "https://accounts.google.com/o/oauth2/auth",
    "token_uri"=> "https://oauth2.googleapis.com/token",
    "auth_provider_x509_cert_url"=> "https://www.googleapis.com/oauth2/v1/certs",
    "client_x509_cert_url"=> "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-hhgur%40campuspoints-e80de.iam.gserviceaccount.com",
    "universe_domain"=> "googleapis.com"
]);

$firebase = (new Factory)
    ->withServiceAccount($serviceAccount);
$auth = $firebase->createAuth();

// Continue with your database connection and HTML rendering
$db = new PDO('mysql:host=localhost;dbname=students', 'root', '');

// Get all students from the database
$sql = "SELECT * FROM student";
$stmt = $db->prepare($sql);
$stmt->execute();
$students = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Student List</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>

<h1>Student List</h1>

<table border="1">
    <tr>
        <th>Student ID</th>
        <th>Student Name</th>
        <th>Email</th>
        <th>Password</th>
        <th>Function</th>
    </tr>

    <?php if ($students): ?>
        <?php foreach ($students as $student): ?>
            <tr>
                <td><?php echo $student['studentID']; ?></td>
                <td><?php echo $student['student_name']; ?></td>
                <td><?php echo $student['email']; ?></td>
                <td><?php echo $student['password']; ?></td>
                <td>
                    <button class= .button-3><a href="edit_studentForm.php?studentID=<?php echo $student['studentID']; ?>">EDIT</a></button>
                    <?php
                    // Create a Firebase user for each student
                    $firebaseUser = createFirebaseUser($auth, $student);
                    if ($firebaseUser) {
                        echo '<div>Firebase UID: ' . $firebaseUser->uid . '</div>';
                    }
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5">No students found.</td>
        </tr>
    <?php endif; ?>
</table>
</body>
</html>
