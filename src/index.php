<?php
    
    try {
        $dbPath = '../DB/currency_exchange.db';
    
        // Connect to the SQLite database
        $pdo = new PDO("sqlite:$dbPath");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        $stmt = $pdo->query("SELECT * FROM Currencies");
        $currencies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        echo "<pre>";
        print_r($currencies);
        echo "</pre>";
    } 
    catch (PDOException $e) {
        echo "PDO Error: " . $e->getMessage();
    }
     catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }


    
?>