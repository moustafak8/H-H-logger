<?php
require_once(__DIR__ . "/ResponsiveService.php");
require_once(__DIR__ . "/../connection/connection.php");
require_once(__DIR__ . "/../models/Habits.php");

class Returnservices {
    public static function findbyuser($user_id) {
        global $connection;
        $sql = "SELECT name, category, unit, user_id, active,VALUE FROM habits WHERE user_id = ? GROUP BY name";
        $query = $connection->prepare($sql);
        $query->bind_param("s", $user_id); 
        $query->execute();
        $result = $query->get_result();
        $objects = [];
        while ($data = $result->fetch_assoc()) {
            $objects[] = new Habits($data);
        }
        return $objects;
    }
}
