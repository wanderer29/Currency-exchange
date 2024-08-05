<?php
namespace Models;

class ExchangeRate extends Record
{
    public function __construct($db)
    {
        parent::__construct($db);
        $this->table = "ExchangeRates";
    }

    public function create($baseCurrencyID, $targetCurrencyID, $rate)
    {
        $statement = $this->db->prepare("INSERT INTO " . $this->table . " (baseCurrencyId, targetCurrencyID, rate) VALUES (?, ?, ?)");
        $statement->bindParam(1, $baseCurrencyID);
        $statement->bindParam(2, $targetCurrencyID);
        $statement->bindParam(3, $rate);

        if ($statement->execute()) {
            return $this->read($baseCurrencyID, $targetCurrencyID);
        }
        return false;

    }

    public function read($baseCurrencyID = null, $targetCurrencyID = null)
    {
        try {
            if ($baseCurrencyID != null && $targetCurrencyID != null) {
                $query = "SELECT * FROM " . $this->table . " WHERE (BaseCurrencyID = :baseCurrencyID AND TargetCurrencyID = :targetCurrencyID)";
    
                $statement = $this->db->prepare($query);
                $statement->bindParam(":baseCurrencyID", $baseCurrencyID);
                $statement->bindParam(":targetCurrencyID", $targetCurrencyID);
                $statement->execute();
        
                return $statement->fetch(\PDO::FETCH_ASSOC);
            } else {
                $query = "SELECT * FROM " . $this->table;
    
                $statement = $this->db->prepare($query);
                $statement->execute();
        
                return $statement->fetchAll(\PDO::FETCH_ASSOC);
            }
        } catch (\PDOException $e) {
            http_response_code(500);
            echo $e->getMessage();
        }
            
    }

    public function update($baseCurrencyID, $targetCurrencyID, $rate)
    {
        $query = "UPDATE " . $this->table . " SET Rate = :rate WHERE (BaseCurrencyID = :baseCurrencyID AND TargetCurrencyID = :targetCurrencyID)";

        $statement = $this->db->prepare($query);
        $statement->bindParam(":baseCurrencyID", $baseCurrencyID);
        $statement->bindParam(":targetCurrencyID", $targetCurrencyID);
        $statement->bindParam(":rate", $rate);
        $statement->execute();

        return $this->read($baseCurrencyID, $targetCurrencyID);
    }
}
