<?php
require_once(__DIR__ . "/../services/ResponsiveService.php");
require_once(__DIR__ . "/../services/Ai_response.php");
class AI_controler{
    function passentry(){
          global $connection;
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
        if ($parsed['parse_status'] === 'success' || $parsed['parse_status'] === 'partial') {
            require_once(__DIR__ . "/../models/Entry.php");
            $entryId = $data['entry_id'];
            $updateData = [
                "parsed_json" => json_encode($parsed),
                "parse_status" => $parsed['parse_status']
            ];
            $updated = Entry::update($connection, $entryId, $updateData);
            if ($updated) {
                echo ResponseService::response(200, ["message" => "Entry processed successfully", "parsed_data" => $parsed]);
            } else {
                echo ResponseService::response(500, ["error" => "Failed to update entry"]);
            }
        } else {
            echo ResponseService::response(400, ["error" => "AI parsing failed", "parsed_data" => $parsed]);
        }

    }
}