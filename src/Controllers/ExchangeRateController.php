<?php
    namespace Controllers;

    require_once __DIR__ . '/../Models/ExchangeRate.php';
    require_once __DIR__ . '/../Models/Currency.php';
    
    use Models\ExchangeRate;
    use Models\Currency;
    
    class ExchangeRateController {

        private $exchangeRate;
        private $currency;

        public function __construct($db) {
            $this->exchangeRate = new ExchangeRate($db);
            $this->currency = new Currency($db);
        }

        public function create() {
            if (isset($_POST["baseCurrencyCode"]) && isset($_POST["targetCurrencyCode"]) && isset($_POST["rate"])) {
                try {
                    $baseCurrencyId = $this->currency->getIdByCode($_POST["baseCurrencyCode"]);
                    $targetCurrencyId = $this->currency->getIdByCode($_POST["targetCurrencyCode"]);
                    $rate = $_POST["rate"];
                    return $this->exchangeRate->create($baseCurrencyId, $targetCurrencyId, $rate);
                }
                catch (\Exception $e) {
                    echo "Error ". $e->getMessage() ." incorrect parameters";
                }
            }
            return false;            
        }

        public function read($baseCurrencyID = null, $targetCurrencyID = null) {
            return $this->exchangeRate->read($baseCurrencyID, $targetCurrencyID);
        }

        public function update($id, $data) {
            if (isset($data["baseCurrencyID"]) && isset($data["targetCurrencyID"]) && isset($data["rate"])) {
                return $this->exchangeRate->update($id, $data["baseCurrencyID"], $data["targetCurrencyID"], $data["rate"]);
            }
            return false;
        }

    }
?>