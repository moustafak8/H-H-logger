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
        echo ResponseService::response(200, ["ai_response" => $parsed]);
    }
}