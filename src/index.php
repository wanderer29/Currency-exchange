<?php
use Models\Currency;

    // require_once "Autoloader.php";

    // Autoloader::register()

    require_once 'Config/Database.php';
    require_once 'Models/Currency.php';
    
    $db = new Config\Database();
    $db = $db->connect();
    
    $currency = new Currency($db);

    foreach ($currency->read() as $currency) {
        echo implode($currency) . "\n";
    }

?>