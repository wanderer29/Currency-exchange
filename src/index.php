<?php

    // require_once "Autoloader.php";

    // Autoloader::register()

    require_once 'Config/Database.php';
    
    $db = new Config\Database();
    $db->connect();
    

?>