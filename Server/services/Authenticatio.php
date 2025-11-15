<?php
require_once("../connection/connection.php");
$sql = "SELECT * FROM users WHERE username = ? AND password = ?";
$query = $connection->prepare($sql);
$query->execute();
$result = $query->get_result();
$count = mysqli_num_rows($result);
if ($count == 1) {
     echo ResponseService::response(200, ["message" => "User found successfully"]);
} else {
    echo ResponseService::response(200, ["message" => "No user with the given credentials"]);
}
