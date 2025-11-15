<?php
include("../connection/connection.php");

$sql = "CREATE TABLE entries (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  entry_date VARCHAR(255) NOT NULL,
  raw_text TEXT NOT NULL,
  parsed_json TEXT DEFAULT NULL,
  parse_status VARCHAR(50) DEFAULT 'pending',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   user_id INT(11) DEFAULT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
)";
$query = $connection->prepare($sql);
$query->execute();
echo "Table Created!";