<?php
require_once(__DIR__ . "/../connection/connection.php");

function generateAIResponse($text)
{
    global $apiKey; 
   
    $system = "You are a parser for a habit-tracking app. Return ONLY valid JSON (no extra text, no explanation). "
            . "Output must be a single JSON object with these fields: "
            . "`date` (ISO YYYY-MM-DD or null), `items` (array of objects with habit_key, value, unit, raw_span, confidence), "
            . "`unrecognized_text` (string), `parse_status` ('success'|'partial'|'failed'). "
            . "If uncertain about a value, set confidence to a low number (0-1).";

    $userContent = "Parse this log into the required JSON format. Text: <<<" . $text . ">>>";

    $payload = [
        "model" => "gpt-4o-mini",
        "messages" => [
            ["role" => "system", "content" => $system],
            ["role" => "user",   "content" => $userContent]
        ],
        "max_tokens" => 1000,
        "temperature" => 0.2
    ];
    $headers = [
        "Authorization: Bearer $apiKey",
        "Content-Type: application/json",
    ];
    $ch = curl_init("https://api.openai.com/v1/chat/completions");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    curl_close($ch);

    if (!$response) return json_encode(["error" => "No response from OpenAI API"]);

    $result = json_decode($response, true);
    if (isset($result['error'])) return json_encode(["error" => $result['error']['message']]);

    $content = $result['choices'][0]['message']['content'] ?? "[]";
    $decoded = json_decode($content, true);
    if (json_last_error() !== JSON_ERROR_NONE) return json_encode(["error" => "Invalid JSON from AI"]);
    return json_encode($decoded);
}
