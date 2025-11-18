<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");
$connection = new mysqli("localhost", "root", "", "habit&health");
$apiKey="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";
if ($connection->connect_error) {
    die("connection error:" . $connection->connect_error);
}
