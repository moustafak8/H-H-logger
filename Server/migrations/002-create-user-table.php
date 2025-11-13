<?php
include("../connection/connection.php");

$sql = "CREATE TABLE users (
  id int(11) AUTO_INCREMENT PRIMARY KEY,
  username varchar(255) NOT NULL,
  password Text NOT NULL,
  role_id int(11) DEFAULT NULL,
  FOREIGN KEY (role_id) REFERENCES roles(role_id) ON DELETE SET NULL
)";

$query = $connection->prepare($sql);
$query->execute();

echo "Table Created!";
