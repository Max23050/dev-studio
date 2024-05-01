<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "form_data";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connection_error);
}

$filmNumber = $_GET['film_number'];
$response = ['type' => '', 'email' => '', 'address' => ''];

// Получение типа заказа из базы данных
$sql = "SELECT negatives, email, address FROM submissions WHERE film_number = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $filmNumber);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $response['type'] = $row['negatives'];
    $response['email'] = $row['email'];
    $response['address'] = $row['address'];
}

echo json_encode($response);

$stmt->close();
$conn->close();

?>