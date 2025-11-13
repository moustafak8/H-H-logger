<?php
include("../connection/connection.php");

$sql = "CREATE TABLE IF NOT EXISTS roles(
  role_id int(11) NOT NULL PRIMARY KEY,
  role_name varchar(255) NOT NULL
)";

$query = $connection->prepare($sql);
$query->execute();

echo "Table(s) Created!";

?>