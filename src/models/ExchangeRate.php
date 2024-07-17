<?php
    class ExchangeRate extends Record {
        public function __construct($db, $table) {
            parent::__construct($db);
            $this->table = $table;
        }

        public function create($baseCurrencyID, $targetCurrencyID, $rate) {
            $query = "INSERT INTO " . $this->table . " (BaseCurrencyID, TargetCurrencyID, Rate) ". "(:baseCurrencyID, :targetCurrencyID, :rate";
            
            $statement = $this->db->prepare($query);
            $statement->bindParam(":baseCurrencyID", $baseCurrencyID);
            $statement->bindParam(":targetCurrencyID", $targetCurrencyID);
            $statement->bindParam(":rate", $rate);
            
            return $statement->execute();
        }

        public function read($baseCurrencyID, $targetCurrencyID) {
            $query = "SELECT * FROM " . $this->table . " WHERE (BaseCurrencyID = :baseCurrencyID AND TargetCurrencyID = :targetCurrencyID)";

            $statement = $this->db->prepare($query);
            $statement->bindParam(":baseCurrencyID", $baseCurrencyID);
            $statement->bindParam(":targetCurrencyID", $targetCurrencyID);
            $statement->execute();

            return $statement->fetch(PDO::FETCH_ASSOC);
        }

        public function update($id, $baseCurrencyID, $targetCurrencyID, $rate) {
            $query = "UPDATE " . $this->table . " SET BaseCurrencyID = :baseCurrencyID, TargetCurrencyID = :targetCurrencyID, Rate = :rate WHERE ID = :id";

            $statement = $this->db->prepare($query);
            $statement->bindParam(":id", $id);
            $statement->bindParam(":baseCurrencyID", $baseCurrencyID);
            $statement->bindParam(":targetCurrencyID", $targetCurrencyID);
            $statement->bindParam(":rate", $rate);

            return $statement->execute();
        }
    }
?>