<?php
    class Currency extends Record {
        public function __construct($db) {
            parent::__construct($db);
            $this->table = "Currencies";
        }

        public function create($code, $fullName, $sign) {
            $query = "INSERT INTO " . $this->table . "(Code, FullName, Sign)" . "(:code, :fullName, :sign)";
            
            $statement = $this->db->prepare($query);
            $statement->bindParam(":code", $code);
            $statement->bindParam(":fullName", $fullName);
            $statement->bindParam(":sign", $sign);

            return $statement->execute();
        }

        public function read($code) {
            $query = "SELECT * FROM " . $this->table . "WHERE Code = :code";

            $statement = $this->db->prepare($query);
            $statement->bindParam(":code", $code);
            $statement->execute();

            return $statement->fetch(PDO::FETCH_ASSOC);
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
    }
?>