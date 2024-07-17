<?php
    namespace Routes;

    use Config\Database;
    use Controllers\CurrencyController;
    use Controllers\ExchangeRateController;

    $db = new Database();
    $db = $db->connect();
    $currencyController = new CurrencyController($db);
    $exchangeRateController = new ExchangeRateController($db);

    $requestMethod = $_SERVER['REQUEST_METHOD'];
    $path = explode('/', $_SERVER['REQUEST_URI']);

    if ($path[1] == "currency") {
        
    }
    

?>