<?php
require_once(__DIR__ . "/../connection/connection.php");
class Ai_response
{
    public static function generateAIResponse($text)
    {
        global $apiKey;

        $today = date('Y-m-d');
        $system = "You are a parser for a habit-tracking app. Return ONLY valid JSON (no extra text, no explanation). "
            . "Output must be a single JSON object with these fields: "
            . "`date` (ISO YYYY-MM-DD, use {$today}), `items` (array of objects with habit,category of the habit ('Health' | 'sport' | 'based on the habit type'), value, unit, raw_span, confidence), "
            . "`unrecognized_text` (string), `parse_status` ('success'|'partial'|'failed'). "
            . "Always return the habit as a noun (e.g., 'sleeping', 'running', 'reading') regardless of the verb form in the input text. "
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
            "Authorization: Bearer " . $apiKey,
            "Content-Type: application/json",
        ];
        $ch = curl_init("https://api.openai.com/v1/chat/completions");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (!$response) return json_encode(["error" => "No response from AI API"]);

        $result = json_decode($response, true);
        if ($httpCode !== 200) {
            $errorMsg = isset($result['error']) ? $result['error']['message'] : "HTTP $httpCode error";
            return json_encode(["error" => $errorMsg]);
        }

        $content = $result['choices'][0]['message']['content'] ?? "";
        if (empty($content)) return json_encode(["error" => "Empty AI response"]);

        $decoded = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) return json_encode(["error" => "Invalid JSON from AI: " . json_last_error_msg()]);
        return json_encode($decoded);
    }
     public static function generateAIsummary($habit, $text){
         global $apiKey;
        $system = "You are an AI assistant for a habit-tracking app. The user provides progress data in JSON format for a specific habit. Provide a concise summary of their habit progress over the period, including averages, trends, and one actionable suggestion to improve specifically for that habit. Return ONLY valid JSON (no extra text, no explanation). Output must be a single JSON object with a 'summary' field containing the text.";
        $userContent = "Habit: $habit. Progress data: " . $text;

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
            "Authorization: Bearer " . $apiKey,
            "Content-Type: application/json",
        ];
        $ch = curl_init("https://api.openai.com/v1/chat/completions");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (!$response) return json_encode(["error" => "No response from AI API"]);

        $result = json_decode($response, true);
        if ($httpCode !== 200) {
            $errorMsg = isset($result['error']) ? $result['error']['message'] : "HTTP $httpCode error";
            return json_encode(["error" => $errorMsg]);
        }

        $content = $result['choices'][0]['message']['content'] ?? "";
        if (empty($content)) return json_encode(["error" => "Empty AI response"]);

        $decoded = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) return json_encode(["error" => "Invalid JSON from AI: " . json_last_error_msg()]);
        return json_encode($decoded);
     }
}
