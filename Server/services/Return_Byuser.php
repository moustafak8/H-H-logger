<?php
require_once(__DIR__ . "/ResponsiveService.php");
require_once(__DIR__ . "/../connection/connection.php");
require_once(__DIR__ . "/../models/Habits.php");
require_once(__DIR__ . "/../models/Entry.php");

class Returnservices
{
    public static function findbyuser($user_id)
    {
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

    public static function updateByName($name, $new_name, $user_id)
    {
        global $connection;
        $sql = "UPDATE habits SET name = ? WHERE name = ? AND user_id = ?";
        $query = $connection->prepare($sql);
        $query->bind_param("sss", $new_name, $name, $user_id);
        return $query->execute();
    }
    public static function deleteByName($name, $user_id)
    {
        global $connection;
        $sql = "DELETE FROM habits WHERE name = ? AND user_id = ?";
        $query = $connection->prepare($sql);
        $query->bind_param("ss", $name, $user_id);
        return $query->execute();
    }
    public static function getHabitProgress($user_id, $habit_name, $start_date, $end_date)
    {
        global $connection;
        $sql = "SELECT DATE(created_at) as date, SUM(VALUE) as value FROM habits WHERE user_id = ? AND name = ? AND DATE(created_at) BETWEEN ? AND ? GROUP BY DATE(created_at) ORDER BY DATE(created_at)";
        $query = $connection->prepare($sql);
        $query->bind_param("ssss", $user_id, $habit_name, $start_date, $end_date);
        $query->execute();
        $result = $query->get_result();
        $progress = [];
        while ($row = $result->fetch_assoc()) {
            $progress[] = $row;
        }
        return $progress;
    }
}
