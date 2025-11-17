<?php
require_once(__DIR__ . "/../models/Habits.php");
require_once(__DIR__ . "/../services/ResponsiveService.php");
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
        $id = $data['id'];
        $newdata = ["name" => $data['name'], "category" => $data['category'], "unit" => $data['unit'], "user_id" => $data['user_id'], "active" => $data['active']];
        $row = Habits::update($connection, $id, $newdata);
        if ($row) {
            echo ResponseService::response(200, ["message" => "habit Updated successfully"]);
            return;
        } else {
            echo ResponseService::response(500, ["message" => "failed to update habit"]);
            return;
        }
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
