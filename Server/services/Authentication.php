<?php
require_once(__DIR__ . "/ResponsiveService.php");
require_once(__DIR__ . "/../connection/connection.php");
class AuthenticationService {

    public static function authenticate($username, $password) {
        global $connection;
        $hashedPassword = hash('sha256', $password);
        $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
        $query = $connection->prepare($sql);
        $query->bind_param("ss", $username, $hashedPassword);
        $query->execute();
        $result = $query->get_result();
        $count = mysqli_num_rows($result);
        if ($count == 1) {
            $user = $result->fetch_assoc();
            return ["success" => true, "user" => $user];
        } else {
            return ["success" => false, "message" => "Invalid credentials"];
        }
    }
}
