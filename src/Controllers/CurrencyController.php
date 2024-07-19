<?php
    namespace Controllers;

    use Models\Currency;

    class CurrencyController {
        private $currency;
        public function __construct($db) {
            $this->currency = new Currency($db);
        }

        public function create() {
            if (isset($_POST["code"]) && isset($_POST["name"]) && isset($_POST["sign"])) {
                $code = $_POST["code"];
                $name = $_POST["name"];
                $sign = $_POST["sign"];
                
                return $this->currency->create($code, $name, $sign);
            }
            return false;
        }

        public function read($code = null) {
            return $this->currency->read($code);
        }

        public function update($id, $data) {
            if (isset($data["code"]) && isset($data["fullName"]) && isset($data["sign"])) {
                return $this->currency->update($id, $data["code"], $data["fullName"], $data["sign"]);
            }
            return false;
        }
        
        public function getCurrencyIdByCode($code) {
            return $this->currency->getIdByCode($code);
        }
    }
?>