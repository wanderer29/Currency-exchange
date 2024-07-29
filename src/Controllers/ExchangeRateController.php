<?php
    namespace Controllers;

    require_once __DIR__ . '/../Models/ExchangeRate.php';
    require_once __DIR__ . '/../Models/Currency.php';
    
    use Models\ExchangeRate;
    use Models\Currency;
    use Exceptions\ExchangeRateNotFoundException;
    
    class ExchangeRateController {

        private $exchangeRate;
        private $currency;

        public function __construct($db) {
            $this->exchangeRate = new ExchangeRate($db);
            $this->currency = new Currency($db);
        }

        public function create() {
            if (!isset($_POST["baseCurrencyCode"]) || !isset($_POST["targetCurrencyCode"]) || !isset($_POST["rate"])) {
                throw new \InvalidArgumentException("Missing required form field");
            }
            try {
                $baseCurrencyId = $this->currency->getIdByCode($_POST["baseCurrencyCode"]);
                $targetCurrencyId = $this->currency->getIdByCode($_POST["targetCurrencyCode"]);
                $rate = $_POST["rate"];
                return $this->exchangeRate->create($baseCurrencyId, $targetCurrencyId, $rate);
            }
            catch (\Exception $e) {
                throw new \Exception("Database error");
            }        
        }

        public function read($baseCurrencyID = null, $targetCurrencyID = null) {
            try {
                $data = $this->exchangeRate->read($baseCurrencyID, $targetCurrencyID);
                if (!$data) {
                    throw new ExchangeRateNotFoundException("Exchange rate not found for the given currency pair");
                }
                return $data;
            } catch (\Exception $e) {
                throw new \Exception("DB error");
            }
        }

        public function update($baseCurrencyID, $targetCurrencyID) {
            try {
                if (!isset($_POST["rate"])) {
                    throw new \InvalidArgumentException("Missing required form field");
                }

                $input = file_get_contents("php://input");
                parse_str($input, $data);
                if (isset($baseCurrencyID) && isset($targetCurrencyID) && isset($data["rate"])) {
                    return $this->exchangeRate->update($baseCurrencyID, $targetCurrencyID, $data["rate"]);
                }
                return false;
            }
            catch (\Exception $e) {
                throw new \Exception("DB error");
            }
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