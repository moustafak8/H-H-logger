<?php
require_once(__DIR__ . "/../connection/connection.php");
require_once(__DIR__ . "/../models/Entry.php");
require_once(__DIR__ . "/ResponsiveService.php");
class Return_entry{
 public static function findentrybydate($user_id , $date)
    {
        global $connection;
        $sql = "SELECT * FROM entries WHERE user_id=? and entry_date=?";
        $query = $connection->prepare($sql);
        $query->bind_param("ss", $user_id , $date);
        $query->execute();
        $result = $query->get_result();
        $objects = [];
        while ($data = $result->fetch_assoc()) {
            $objects[] = new Entry($data);
        }
        return $objects;
    }
}