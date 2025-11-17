<?php
require_once(__DIR__ . "/../services/ResponsiveService.php");
require_once(__DIR__ . "/../services/Ai_response.php");
require_once(__DIR__ . "/../models/Entry.php");
class AI_controler{
    function processEntry(){
        if ($_SERVER["REQUEST_METHOD"] != 'POST') {
            echo ResponseService::response(405, "Method Not Allowed");
            exit;
        }
        $data = json_decode(file_get_contents("php://input"), true);
        $res = Ai_response::generateAIResponse($data['raw_text']);
        $parsed = json_decode($res, true);

        if (isset($parsed['error'])) {
            echo ResponseService::response(500, ["error" => $parsed['error']]);
            return;
        }

        echo ResponseService::response(200, [
            "message" => "Entry processed successfully",
            "parsed" => $parsed,
            "raw_text" => $data['raw_text'],
            "user_id" => $data['user_id'] ?? null
        ]);
    }

    function saveEntry(){
        global $connection;
        if ($_SERVER["REQUEST_METHOD"] != 'POST') {
            echo ResponseService::response(405, "Method Not Allowed");
            exit;
        }
        $data = json_decode(file_get_contents("php://input"), true);

        $entryData = [
            "entry_date" => $data['parsed']['date'],
            "raw_text" => $data['raw_text'],
            "parsed_json" => json_encode($data['parsed']),
            "parse_status" => $data['parsed']['parse_status'],
            "user_id" => $data['user_id'] ?? null
        ];
        $entry = new Entry($entryData);
        $insertedId = $entry->add($connection, $entryData);

        if ($insertedId) {
            echo ResponseService::response(200, [
                "message" => "Entry saved successfully",
                "entry_id" => $insertedId
            ]);
        } else {
            echo ResponseService::response(500, ["error" => "Failed to save entry"]);
        }
    }
}
