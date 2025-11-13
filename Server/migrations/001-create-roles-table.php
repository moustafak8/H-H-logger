<?php
include("../connection/connection.php");

$sql = "CREATE TABLE roles (
  role_id int(11) NOT NULL,
  role_name varchar(255) NOT NULL
)";

$query = $connection->prepare($sql);
$query->execute();

echo "Table(s) Created!";

?>