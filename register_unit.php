<!DOCTYPE html>
<html>
<head>
    <title>Unit Information</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">
</head>
<body>

<div style="margin: 3% auto;">
    <h1 class="display-6">Unit Information</h1>
    <table border="1" class="table">
    <thead>
        <tr>
            <th scope="col">Student ID</th>
            <th scope="col">Semester</th>
            <th scope="col">Unit ID</th>
            <th scope="col">Unit Name</th>
            <th scope="col">Unit Grade</th>
            <th scope="col">Unit Attendance</th>
            <th scope="col">Function</th>
        </tr>
    </thead>
    <tbody>

        <?php
        require 'vendor/autoload.php';

        use Kreait\Firebase\Factory;

        // Replace with your database connection details
        $hostname = "localhost";
        $username = "root";
        $password = "";
        $dbname = "students";

        // Create a connection to the database
        $conn = new mysqli($hostname, $username, $password, $dbname);

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Initialize Firebase
        $factory = (new Factory)
            ->withServiceAccount(__DIR__.'/google-service.json')
            ->withDatabaseUri('https://campuspoints-e80de-default-rtdb.firebaseio.com/');

        $database = $factory->createDatabase();

        // Check if 'studentID' parameter is set in the URL
        if (isset($_GET['studentID']) && isset($_GET['email'])) {
            $id = $_GET['studentID'];
            $email = $_GET['email']; 

            // Get the Firebase Auth UID of the current student
            $auth = $factory->createAuth();
            $user = $auth->getUserByEmail($email);

            if ($user) {
                $uid = $user->uid; // Retrieve the UID of the user

                // SQL query to retrieve data from the "units" table for a specific student
                $sql = "SELECT * FROM units WHERE studentID = $id";
                $sql = "SELECT studentID, semester, unitID, unit_name, grades, attendance FROM units WHERE studentID = $id";


                // SQL query to retrieve student data where studentID is equal to $id
                $studentQuery = "SELECT * FROM student WHERE studentID = $id";

                // Execute the queries
                $result = $conn->query($sql);
                $studentResult = $conn->query($studentQuery);

                if ($result->num_rows > 0 && $studentResult->num_rows > 0) {


                    // Output data from each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["studentID"] . "</td>";
                        echo "<td>" . $row["semester"] . "</td>";
                        echo "<td>" . $row["unitID"] . "</td>";
                        echo "<td>" . $row["unit_name"] . "</td>";
                        echo "<td>" . $row["grades"] . "</td>";
                        echo "<td>" . $row["attendance"] . "</td>";
                        echo "<td>Edit</td>"; 

                        // Fetch student_name from the student table using studentID
                        $studentQuery = "SELECT studentID, student_name, email FROM student WHERE studentID = " . $row["studentID"];
                        $studentResult = $conn->query($studentQuery);
                        $studentData = $studentResult->fetch_assoc();

                        // //Store the unit data in Firebase along with student_name
                        // $studentData = [
                        //     'studentID' => $row["studentID"],
                        //     'student_name' => $row['student_name'],
                        //     'student_email' => $row['email'],
                        // ];
                        $unitData = [
                            'attendance' => $row["attendance"],
                            'grades' => $row["grades"],
                            'semester' => $row["semester"],
                            'unitID' => $row["unitID"],
                            'unit_name' => $row["unit_name"]
                        ];
                        
                        // Store the unit data under the student's ID in Firebase
                        $reference = $database->getReference('/users/' . $uid . '/units/'. $row["unitID"]);
                        $reference->set($unitData);
                        
                        // Store student data under the user's UID
                        $studentData = [
                            'studentID' => $studentData["studentID"],
                            'student_name' => $studentData["student_name"],
                            'email' => $studentData["email"]
                        ];
                        
                        $studentReference = $database->getReference('/users/' . $uid . '/student/');
                        $studentReference->set($studentData);
                        
                        echo "</tr>";
                        
                    }

                } else {
                    echo "<tr><td colspan='7'>No units found</td></tr>";
                }
            } else {
                echo "User with email $email not found in Firebase Authentication.";
            }
        } else {
            echo "<tr><td colspan='7'>No student ID provided</td></tr>";
        }

        // Close the database connection
        $conn->close();
        ?>
    </tbody>
</table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>
