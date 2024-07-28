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

        public function update($baseCurrencyID, $targetCurrencyID) {
            $input = file_get_contents("php://input");
            parse_str($input, $data);
            if (isset($baseCurrencyID) && isset($targetCurrencyID) && isset($data["rate"])) {
                return $this->exchangeRate->update($baseCurrencyID, $targetCurrencyID, $data["rate"]);
            }
            return false;
        }

        public function convert($fromCode, $toCode, $amount) {
            $fromId = $this->currency->getIdByCode($fromCode);
            $toId = $this->currency->getIdByCode($toCode);
            $usdId = $this->currency->getIdByCode("USD");

            if ($this->read($fromId, $toId)) {
                $exchangeRate = $this->read($fromId, $toId);
                $rate = $exchangeRate["Rate"];
                $convertedAmount = $amount * $rate;

                return [
                    "from" => $fromCode,
                    "to"=> $toCode,
                    "rate"=> $rate,
                    "amount"=> $amount,
                    "convertedAmount"=> $convertedAmount
                ];
            }
            else if ($this->read($toId, $fromId)) {
                $exchangeRate = $this->read($toId, $fromId);
                $rate = $exchangeRate["Rate"];
                $rate = 1 / $rate;
                $convertedAmount = $amount * $rate;

                return [
                    "from" => $fromCode,
                    "to"=> $toCode,
                    "rate"=> round($rate, 2),
                    "amount"=> $amount,
                    "convertedAmount"=> round($convertedAmount, 2)
                ];
            }
            else if ($this->read($usdId, $fromId) && $this->read($usdId, $toId)) {
                $exchangeRateFrom = $this->read($usdId, $fromId);
                $exchangeRateTo = $this->read($usdId, $toId);
                $rateFrom = $exchangeRateFrom["Rate"];
                $rateTo = $exchangeRateTo["Rate"];
                $rate = $rateTo / $rateFrom;
                $convertedAmount = $amount * $rate;

                return [
                    "from" => $fromCode,
                    "to"=> $toCode,
                    "rate"=> round($rate, 2),
                    "amount"=> $amount,
                    "convertedAmount"=> round($convertedAmount, 2)
                ];
            }
            else {
                http_response_code(404);
                return ["Error" => "Exchange rate not found"];
            }
        }

    }
?>