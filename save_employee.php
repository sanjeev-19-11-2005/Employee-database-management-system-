<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Update with your password
$dbname = "employee_portal";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];

    if (!empty($name) && !empty($email)) {
        $query = "INSERT INTO employees (name, email) VALUES ('$name', '$email')";
        if (mysqli_query($conn, $query)) {
            $id = mysqli_insert_id($conn); // Get the last inserted ID
            echo json_encode([
                'status' => 'success',
                'message' => 'Employee added successfully!',
                'id' => $id,
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to add employee.',
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Name and Email are required.',
        ]);
    }
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    var_dump($_POST);
    exit;
}


$conn->close();
?>
