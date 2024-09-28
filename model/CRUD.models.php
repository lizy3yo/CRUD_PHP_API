<?php

interface IExample {
    public function getAll();
    public function insertData($data);
    public function getSingle($data);
    public function updateData($data);
    public function deleteData($data);
}

class CRUD_models implements IExample {

    protected $pdo;
    protected $glb;
    protected $table_name = "users";

    public function __construct(\PDO $pdo, GlobalMethods $glb) {
        $this->pdo = $pdo;
        $this->glb = $glb;
    }

    public function hello() {
        $data = [
            "sample" => "Hello"
        ];
        return $data;
    }

    public function getAll() {
        $sql = "SELECT * FROM " . $this->table_name;
        // $sql = "CALL getAllItem();";
        try {
            $stmt = $this->pdo->prepare($sql);
            if($stmt->execute()) {
                $data = $stmt->fetchAll();
                if($stmt->rowCount() >= 1) {
                    return $this->glb->responsePayload($data, "SUCESS" , "ALL DATA has been Pulled", 200);
                } else {
                    return $this->glb->responsePayload(null, "FAILED" , "No data pulled", 404);
                }
            }
        } catch(\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getSingle($data) {
        $sql = "SELECT * FROM " . $this->table_name  . " WHERE id = ?";
        try {
            $stmt = $this->pdo->prepare($sql);
            if ($stmt->execute([$data->id])){
                $data =  $stmt->fetchAll();
                if ($stmt->rowCount() >= 1){
                    return $this->glb->responsePayload($data, "SUCESS" , "A SINGLE USER has been Pulled", 200);
                }else{
                    return $this->glb->responsePayload(null, "FAILED" , "FAILED to pull a single user", 404);
                }
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
      
        
    }

    public function insertData($data) {
        $sql = "INSERT INTO " . $this->table_name . " (firstname, lastname, is_admin) VALUES (?, ?, ?);";
        try {
            $stmt = $this->pdo->prepare($sql);
            if ($stmt->execute([$data->firstname, $data->lastname, $data->is_admin])) {
                return $this->glb->responsePayload(null, "SUCESS", "The Data is INSERTED", 200);
            } else {
                return $this->glb->responsePayload(null, "FAILED", "FAILED to Insert the Data", 404);
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }
    

    public function updateData($data) {
        $sql = "UPDATE " . $this->table_name . " SET firstname = ?, lastname = ?, is_admin = ? WHERE id = ?;";
        try {
            $stmt = $this->pdo->prepare($sql);
            if($stmt->execute([$data->id, $data->firstname, $data->lastname, $data->is_admin])) {
                return $this->glb->responsePayload(null, "SUCESS" , "The User has been SUCCESSFULLY CHANGED", 200);
            } else {
                return $this->glb->responsePayload(null, "FAILED" , "FAILED to Change the Data", 404);
            }

        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function deleteData($data) {
        $sql = "DELETE FROM " . $this->table_name . " WHERE id = ?;";
        try {
            $stmt = $this->pdo->prepare($sql);
            if($stmt->execute([$data->id])) {
                return $this->glb->responsePayload(null, "SUCESS" , "The Data has been DELETED", 200);
            } else {
                return $this->glb->responsePayload(null, "FAILED" , "FAILED to Delete the Data", 404);
            }

        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }
    
}
