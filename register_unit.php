<!DOCTYPE html>
<html>
<head>
    <title>Unit Information</title>
</head>
<body>

<h1>Unit Information</h1>

<table border="1">
    <thead>
        <tr>
            <th>Student ID</th>
            <th>Semester</th>
            <th>Unit ID</th>
            <th>Unit Name</th>
            <th>Unit Grade</th>
            <th>Unit Attendance</th>
            <th>Function</th>
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
                        $studentQuery = "SELECT student_name FROM student WHERE studentID = " . $row["studentID"];
                        $studentResult = $conn->query($studentQuery);
                        $studentData = $studentResult->fetch_assoc();

                        // Store the unit data in Firebase along with student_name
                        $unitData = [
                            'studentID' => $row["studentID"],
                            'student_name' => $studentData['student_name'], // Include student_name
                            'semester' => $row["semester"],
                            'unitID' => $row["unitID"],
                            'unit_name' => $row["unit_name"],
                            'grades' => $row["grades"],
                            'attendance' => $row["attendance"],
                        ];

                        // Store the unit data under the student's ID in Firebase
                        $reference = $database->getReference('/users/' . $uid . '/' . $row["studentID"] . '/' . $studentData['student_name']. '/units/' . $row["unitID"]);
                        $reference->set($unitData);

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

</body>
</html>
