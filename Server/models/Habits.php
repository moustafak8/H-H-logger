<?php
include("Model.php");

class Habits extends Model {
    private string $name;
    private string $category;
    private int $VALUE;
    private string $unit;
    private string $active;
    private string $user_id;
    

    protected static string $table = "habits";

    public function __construct(array $data){
        $this->name = $data["name"];
        $this->category = $data["category"];
        $this->unit = $data["unit"];
        $this->VALUE = $data["VALUE"];
        $this->active = $data["active"];
        $this->user_id = $data["user_id"];
    }

    public function getemail(){
        return $this->name;
    }

    public function setemail(string $name){
        $this->name = $name;
    }
    public function __toString(){
        return  $this->name . " | " . $this->category. " | " . $this->unit. " | " . $this->VALUE;
    }
    
    public function toArray(){
        return ["name" => $this->name, "category" => $this->category, "unit" => $this->unit ,"value" => $this->VALUE ,"user_id"=>$this->user_id,"active"=>$this->active];
    }

}

?>