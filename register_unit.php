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

        // Check if 'studentID' parameter is set in the URL
        if (isset($_GET['studentID'])) {
            $id = $_GET['studentID'];

            // SQL query to retrieve data from the "units" table for a specific student
            $sql = "SELECT * FROM units WHERE studentID = $id";

            // Execute the query
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Initialize Firebase
                $factory = (new Factory)
                    ->withServiceAccount(__DIR__.'/google-service.json') // Use a relative path
                    ->withDatabaseUri('https://campuspoints-e80de-default-rtdb.firebaseio.com/'); // Firebase Realtime Database URL

                $database = $factory->createDatabase();

                // Output data from each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["studentID"] . "</td>";
                    echo "<td>" . $row["semester"] . "</td>";
                    echo "<td>" . $row["unitID"] . "</td>";
                    echo "<td>" . $row["unit_name"] . "</td>";
                    echo "<td>" . $row["grades"] . "</td>";
                    echo "<td>" . $row["attendance"] . "</td>";
                    echo "<td>Edit</td>"; // You can modify this column as needed

                    // Store the unit data in Firebase
                    $unitData = [
                        'studentID' => $row["studentID"],
                        'semester' => $row["semester"],
                        'unitID' => $row["unitID"],
                        'unit_name' => $row["unit_name"],
                        'grades' => $row["grades"],
                        'attendance' => $row["attendance"],
                    ];

                   // Store the unit data under the student's ID in Firebase
                    $reference = $database->getReference('/users/' . $row["studentID"] . '/units/' . $row["unitID"]);
                    $reference->set($unitData);

                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No units found</td></tr>";
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
