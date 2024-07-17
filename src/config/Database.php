<?php
    namespace Config;
    class Database {
        private $db;
        public function connect() {
            $dbPath = '../DB/currency_exchange.db';

            //Connect to database
            $this->db = new \PDO("sqlite:$dbPath");
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $this->db;
        }
    }
?>