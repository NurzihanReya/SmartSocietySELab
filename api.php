<?php
$host = 'localhost';
$db = 'smartsociety1';
$user = 'root';
$password = '';

$connection = new mysqli($host, $user, $password, $db);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$query = "SELECT u_id, name, email, dob, phone, street, house FROM users ";
$result = $connection->query($query);

$userData = array();
while ($row = $result->fetch_assoc()) {
    $userData[] = $row;
}

$connection->close();

header('Content-Type: application/json');
echo json_encode($userData);
?>