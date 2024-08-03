<?php
    namespace Models;
    require_once 'Models/Record.php';
    require_once 'Config/Database.php';
    
    class Currency extends Record {
        public function __construct($db) {
            parent::__construct($db);
            $this->table = "Currencies";
        }

        public function create($code, $fullName, $sign) {
            // $query = "INSERT INTO " . $this->table . " (Code, FullName, Sign)" . "(:code, :fullName, :sign)";
            
            // $statement = $this->db->prepare($query);
            // $statement->bindParam(":code", $code);
            // $statement->bindParam(":fullName", $fullName);
            // $statement->bindParam(":sign", $sign);

            // return $statement->execute();

            try {
                $statement = $this->db->prepare("INSERT INTO currencies (code, fullName, sign) VALUES (?, ?, ?)");
                $statement->bindParam(1, $code);
                $statement->bindParam(2, $fullName);
                $statement->bindParam(3, $sign);
    
                if ($statement->execute()) {
                    return $this->read($code);
                }
                
                return false;
            }
            catch (\PDOException $e) {
                echo $e->getMessage();
                http_response_code(500);
                return false;
            }
        }

        public function read($code = null) {
            if ($code != null) {
                $query = "SELECT * FROM " . $this->table . " WHERE Code = :code";

                $statement = $this->db->prepare($query);
                $statement->bindParam(":code", $code);
                $statement->execute();

                return $statement->fetch(\PDO::FETCH_ASSOC);
            } 
            else {
                $query = "SELECT * FROM " . $this->table;

                $statement = $this->db->prepare($query);
                $statement->execute();

                return $statement->fetchAll(\PDO::FETCH_ASSOC);
                }
        }

        public function update($id, $code, $fullName, $sign) {
            $query = "UPDATE " . $this->table . "SET Code = :code, FullName = :fullName, Sign = :sign WHERE ID = :id";

            $statement = $this->db->prepare($query);
            $statement->bindParam(":code", $code);
            $statement->bindParam(":fullName", $fullName);
            $statement->bindParam(":sign", $sign);
            $statement->bindParam(":id", $id);

            return $statement->execute();
        }

        public function getIdByCode($code) {
            $query = "SELECT ID FROM " . $this->table . " WHERE Code = :code";
            $statement = $this->db->prepare($query);
            $statement->bindParam(":code", $code);
            $statement->execute();
            
            return $statement->fetchColumn();
        }
    }
