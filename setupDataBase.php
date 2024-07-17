<?php
    try {
        $db = new PDO("sqlite:DB/currency_exchange.db");

        //Currencies table
        $db->exec("CREATE TABLE IF NOT EXISTS Currencies (
            ID INTEGER PRIMARY KEY AUTOINCREMENT,
            Code VARCHAR NOT NULL UNIQUE,
            FullName VARCHAR NOT NULL,
            Sign VARCHAR        
        )");

        //ExchangeRates table
        $db->exec("CREATE TABLE IF NOT EXISTS ExchangeRates (
            ID INTEGER PRIMARY KEY AUTOINCREMENT,
            BaseCurrencyID INTEGER NOT NULL,
            TargetCurrencyID INTEGER NOT NULL,
            Rate DECIMAL(6) NOT NULL,
            FOREIGN KEY (BaseCurrencyId) REFERENCES Currencies(id),
            FOREIGN KEY (TargetCurrencyId) REFERENCES Currencies(id),
            UNIQUE (BaseCurrencyId, TargetCurrencyId)
        )"); 
    }

    catch (PDOException $e) {
        echo "Mistake: " . $e->getMessage();
    }
?>