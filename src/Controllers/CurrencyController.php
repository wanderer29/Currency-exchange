<?php
    namespace Controllers;

    use Models\Currency;
    use Exceptions\CurrencyNotFoundException;
    use Exceptions\CurrencyAlreadyExistsException;
    use Exceptions\MissingFieldException;

    class CurrencyController {
        private $currency;
        public function __construct($db) {
            $this->currency = new Currency($db);
        }

        public function create() {
            try {
                if (isset($_POST["code"]) && isset($_POST["name"]) && isset($_POST["sign"])) {
                    $code = $_POST["code"];
                    $name = $_POST["name"];
                    $sign = $_POST["sign"];
                    
                    if ($this->read($_POST["code"])) {
                        http_response_code(409);
                        echo "A currency with this code already exists";
                    }
                    return $this->currency->create($code, $name, $sign);
                }
                else {
                    http_response_code(400);
                    echo "Required form field is missing";
                }
                return false;
            }
            catch (CurrencyAlreadyExistsException $e) {
                throw $e;
            } catch (MissingFieldException $e) {
                throw $e;
            } catch (\Exception $e) {
                throw new \Exception("Database error");
            }
        }

        public function read($code = null) {
            try {
                if ($code === null) {
                    return $this->currency->read();
                }
                else {
                    $currency = $this->currency->read($code);
                    if (!$currency) {
                        throw new CurrencyNotFoundException("Currency not found");
                    }
                    return $currency;
                }
            } 
            catch(CurrencyNotFoundException $e) {
                error_log("Currency not found: " . $e->getMessage());
                throw $e;
            }
            catch (\Exception $e) {
                error_log("DB error: " . $e->getMessage());
                throw new \Exception("Database error");
            }
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