<?php
include("../connection/connection.php");

$sql = "CREATE TABLE habits (
  id int(11) AUTO_INCREMENT PRIMARY KEY,
  name varchar(255) NOT NULL,
category varchar(255) NOT NULL,
 unit varchar(255) NOT NULL,
  user_id int(11) DEFAULT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
)";

$query = $connection->prepare($sql);
$query->execute();

echo "Table Created!";
