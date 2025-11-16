<?php
require_once(__DIR__ . "/../services/ResponsiveService.php");
require_once(__DIR__ . "/../services/Ai_response.php");
require_once(__DIR__ . "/../models/Entry.php");
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
        $entryData = [
            "entry_date" => $parsed['date'] ?? date('Y-m-d'),
            "raw_text" => $data['raw_text'],
            "parsed_json" => json_encode($parsed),
            "parse_status" => $parsed['parse_status'] ?? 'failed',
            "user_id" => $data['user_id'] ?? null
        ];
        $entry = new Entry($entryData);
        $insertedId = $entry->add($connection, $entryData);

        if ($insertedId) {
            echo ResponseService::response(200, [
                "message" => "Entry processed and saved successfully",
                "entry_id" => $insertedId,
                "ai_response" => $parsed
            ]);
        } else {
            echo ResponseService::response(500, ["error" => "Failed to save entry"]);
        }
    }
}
