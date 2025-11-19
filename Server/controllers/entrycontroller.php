<?php
require_once(__DIR__ . "/../models/Entry.php");
require_once(__DIR__ . "/../services/ResponsiveService.php");
require_once(__DIR__ . "/../services/Return_entry.php");
require_once(__DIR__ . "/../connection/connection.php");


class entrycontroller
{
    function getentries()
    {
        global $connection;
        if (isset($_GET["id"])) {
            $id = $_GET["id"];
            $entry = Entry::find($connection, $id);
            echo ResponseService::response(200, $entry->toArray());
            return;
        } 
        
        elseif (isset($_GET["user_id"]) && isset($_GET["date"])) {
            $user_id = $_GET["user_id"];
            $date = $_GET["date"];
            $entries = Return_entry::findentrybydate($user_id, $date);
            $arr = [];
            foreach ($entries as $entry) {
                $arr[] = $entry->toArray();
            }
            echo ResponseService::response(200, $arr);
            return;
        } 
            
            else {
            $entries = Entry::findAll($connection);
            $arr = [];
            foreach ($entries as $entry) {
                $arr[] = $entry->toArray();
            }
            echo ResponseService::response(200, $arr);
            return;
        }
    }
    function newentry()
    {
        global $connection;
        if ($_SERVER["REQUEST_METHOD"] != 'POST') {
            echo ResponseService::response(405, "Method Not Allowed");
            exit;
        }
        $data = json_decode(file_get_contents("php://input"), true);
        $entry = [
            "entry_date" => $data['entry_date'],
            "raw_text" => $data['raw_text'],
            "parsed_json" => $data['parsed_json'],
            "parse_status" => "pending",
            "user_id" => $data['user_id']
        ];
        $new = new Entry($entry);
        $insertedId = $new->add($connection, $entry);
        if ($insertedId) {
            echo ResponseService::response(200, ["message" => "entry added successfully", "id" => $insertedId]);
        } else {
            echo ResponseService::response(500, ["error" => "Failed to add entry"]);
        }
    }
    function updateentry()
    {
        global $connection;
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'];
        $newdata = [
            "entry_date" => $data['entry_date'],
            "raw_text" => $data['raw_text'],
            "parsed_json" => $data['parsed_json'],
            "parse_status" => $data['parse_status'] ?? "pending",
            "user_id" => $data['user_id']
        ];
        $row = Entry::update($connection, $id, $newdata);
        if ($row) {
            echo ResponseService::response(200, ["message" => "entry Updated successfully"]);
            return;
        } else {
            echo ResponseService::response(500, ["message" => "failed to update entry"]);
            return;
        }
    }
    function deleteentry()
    {
        global $connection;

        if (isset($_GET["id"])) {
            $id = $_GET["id"];
            $entry = Entry::Deleterow($connection, $id);
            echo ResponseService::response(200, ["message" => "entry deleted successfully"]);
            return;
        } else {
            echo ResponseService::response(500, ["message" => "failed to delete entry"]);
            return;
        }
    }
}
