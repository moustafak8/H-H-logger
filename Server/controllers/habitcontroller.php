<?php
require_once(__DIR__ . "/../models/Habits.php");
require_once(__DIR__ . "/../services/ResponsiveService.php");
require_once(__DIR__ . "/../services/Return_Byuser.php");
require_once(__DIR__ . "/../connection/connection.php");

class habitcontroller
{
    function gethabits()
    {
        global $connection;
        if (isset($_GET["id"])) {
            $id = $_GET["id"];
            $car = Habits::find($connection, $id);
            echo ResponseService::response(200, $car->toArray());
            return;
        } elseif (isset($_GET["user_id"])) {
            $user_id = $_GET["user_id"];
            $habits = Returnservices::findbyuser($user_id);
            $arr = [];
            foreach ($habits as $habit) {
                $arr[] = $habit->toArray();
            }
            echo ResponseService::response(200, $arr);
            return;
        } else {
            $cars = Habits::findAll($connection);
            $arr = [];
            foreach ($cars as $car) {
                $arr[] = $car->toArray();
            }
            echo ResponseService::response(200, $arr);
            return;
        }
    }
    function newhabit()
    {
        global $connection;
        if ($_SERVER["REQUEST_METHOD"] != 'POST') {
            echo ResponseService::response(405, "Method Not Allowed");
            exit;
        }
        $data = json_decode(file_get_contents("php://input"), true);
        $habit = ["name" => $data['name'], "category" => $data['category'], "unit" => $data['unit'], "VALUE" => $data['VALUE'], "user_id" => $data['user_id'], "active" => $data['active']];
        $new = new Habits($habit);
        $insertedId = $new->add($connection, $habit);
        if ($insertedId) {
            echo ResponseService::response(200, ["message" => "habit added successfully", "id" => $insertedId]);
        } else {
            echo ResponseService::response(500, ["error" => "Failed to add habit"]);
        }
    }
    function updatehabit()
    {
        global $connection;
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['id'])) {
            $id = $data['id'];
            $newdata = ["name" => $data['name'], "category" => $data['category'], "unit" => $data['unit'], "user_id" => $data['user_id'], "active" => $data['active']];
            $row = Habits::update($connection, $id, $newdata);
        } elseif (isset($data['name']) && isset($data['new_name'])) {
            $name = $data['name'];
            $new_name = $data['new_name'];
            $user_id = $data['user_id'];
            $row = Returnservices::updateByName($name, $new_name, $user_id);
        } else {
            echo ResponseService::response(400, ["message" => "Invalid update parameters"]);
            return;
        }
        if ($row) {
            echo ResponseService::response(200, ["message" => "habit Updated successfully"]);
            return;
        } else {
            echo ResponseService::response(500, ["message" => "failed to update habit"]);
            return;
        }
    }
    function getHabitProgress()
    {
        global $connection;
        if (!isset($_GET["user_id"]) || !isset($_GET["habit_name"]) || !isset($_GET["start_date"]) || !isset($_GET["end_date"])) {
            echo ResponseService::response(400, ["message" => "Missing required parameters"]);
            return;
        }

        $user_id = $_GET["user_id"];
        $habit_name = $_GET["habit_name"];
        $start_date = $_GET["start_date"];
        $end_date = $_GET["end_date"];

        $progress = Returnservices::getHabitProgress($user_id, $habit_name, $start_date, $end_date);
        echo ResponseService::response(200, $progress);
    }

    function deletehabit()
    {
        global $connection;

        if (isset($_GET["id"])) {
            $id = $_GET["id"];
            $habit = Habits::Deleterow($connection, $id);
            echo ResponseService::response(200, ["message" => "habit deleted successfully"]);
            return;
        } else {
            echo ResponseService::response(500, ["message" => "failed to delete habit"]);
            return;
        }
    }
}
