<?php
    namespace Controllers;

    use Models\ExchangeRate;
    
    class ExchangeRateController {

        private $exchangeRate;

        public function __construct($db) {
            $this->exchangeRate = new ExchangeRate($db);
        }

        public function create($data) {
            if (isset($data["baseCurrencyID"]) && isset($data["targetCurrencyID"]) && isset($data["rate"])) {
                return $this->exchangeRate->create($data["baseCurrencyID"], $data["targetCurrencyID"], $data["rate"]);
            }
            return false;
        }

        public function read($baseCurrencyID = null, $targetCurrencyID = null) {
            return $this->read($baseCurrencyID, $targetCurrencyID);
        }

        public function update($id, $data) {
            if (isset($data["baseCurrencyID"]) && isset($data["targetCurrencyID"]) && isset($data["rate"])) {
                return $this->exchangeRate->update($id, $data["baseCurrencyID"], $data["targetCurrencyID"], $data["rate"]);
            }
            return false;
        }

    }
?>