<?php
include("Model.php");

class Entry extends Model {
    private string $entry_date;
    private string $raw_text;
    private string $parsed_json;
    private string $parse_status;
    private ?int $user_id;

    protected static string $table = "entries";
    public function __construct(array $data){
        $this->entry_date = $data["entry_date"];
        $this->raw_text = $data["raw_text"];
        $this->parsed_json = $data["parsed_json"] ?? null;
        $this->parse_status = $data["parse_status"] ?? "pending";
        $this->user_id = $data["user_id"] ?? null;
    }

    public function getEntryDate(){
        return $this->entry_date;
    }

    public function setEntryDate(string $entry_date){
        $this->entry_date = $entry_date;
    }

    public function getRawText(){
        return $this->raw_text;
    }

    public function setRawText(string $raw_text){
        $this->raw_text = $raw_text;
    }

    public function getParsedJson(){
        return $this->parsed_json;
    }

    public function setParsedJson(?string $parsed_json){
        $this->parsed_json = $parsed_json;
    }

    public function getParseStatus(){
        return $this->parse_status;
    }

    public function setParseStatus(string $parse_status){
        $this->parse_status = $parse_status;
    }

    public function getUserId(){
        return $this->user_id;
    }

    public function setUserId(?int $user_id){
        $this->user_id = $user_id;
    }

    public function __toString(){
        return $this->entry_date . " | " . $this->raw_text . " | " . $this->parse_status;
    }

    public function toArray(){
        return [
            "entry_date" => $this->entry_date,
            "raw_text" => $this->raw_text,
            "parsed_json" => $this->parsed_json,
            "parse_status" => $this->parse_status,
            "user_id" => $this->user_id
        ];
    }
}
?>
