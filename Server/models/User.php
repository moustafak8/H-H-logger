<?php
include("Model.php");

class Car extends Model {
    private string $username;
    private string $password;
    private string $role_id;

    protected static string $table = "users";

    public function __construct(array $data){
        $this->username = $data["username"];
        $this->password = $data["password"];
        $this->role_id = $data["role_id"];
    }

    public function getemail(){
        return $this->username;
    }

    public function setemail(string $username){
        $this->username = $username;
    }
    public function __toString(){
        return $this->id . " | " . $this->username . " | " . $this->password. " | " . $this->role_id;
    }
    
    public function toArray(){
        return ["email" => $this->username, "pass" => $this->password, "role_id" => $this->role_id];
    }

}

?>