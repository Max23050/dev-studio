<?php
$orderId = $_POST['orderId'];
$newStatus = $_POST['status'];


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "form_data";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connection_error);
}

$stmt = $conn->prepare("UPDATE submissions SET status = ? WHERE id = ?");
$stmt->bind_param("si", $newStatus, $orderId);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Success";
} else {
    echo "Error";
}

$stmt->close();
$conn->close();


?>