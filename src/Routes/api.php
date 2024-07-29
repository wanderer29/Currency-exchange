<?php
    namespace Routes;

    require_once __DIR__ . '/../Controllers/CurrencyController.php';
    require_once __DIR__ . '/../Controllers/ExchangeRateController.php';
    require_once __DIR__ . '/../Config/Database.php';

    use Config\Database;
    use Controllers\CurrencyController;
    use Controllers\ExchangeRateController;
    use Exceptions\CurrencyNotFoundException;
    use Exceptions\CurrencyAlreadyExistsException;
    use Exceptions\MissingFieldException;
    use Exceptions\ExchangeRateNotFoundException;


    class Api {
        private $db;
        private $currencyController;
        private $exchangeRateController;

        public function __construct() {
            $this->db = new Database();
            $this->db = $this->db->connect();
            $this->currencyController = new CurrencyController($this->db);
            $this->exchangeRateController = new ExchangeRateController($this->db);
        }

        public function handleRequest() {
            $requestMethod = $_SERVER['REQUEST_METHOD'];
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $path = explode('/', trim($uri, '/'));
            // $path = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

            switch ($path[0]) {
                case "currencies":
                    $this->handleCurrencies($requestMethod, $path);
                    break;
                case "exchangeRates":
                    $this->handleExchangeRates($requestMethod, $path);
                    break;
                case "exchange":
                    $this->handleExchange($requestMethod);
                    break;
                default:
                    http_response_code(404);
                    echo json_encode(["Error" => "Route was not found"]);
            }
        }

        private function handleCurrencies($requestMethod, $path) {
            try {
                if (count($path) == 1) {
                    switch($requestMethod) {
                        case "GET": 
                            echo json_encode($this->currencyController->read());
                            http_response_code(200);
                            break;
                        case "POST":
                            $result = $this->currencyController->create();
                            http_response_code(201);
                            echo json_encode($result);
                            break;
                        default:
                            http_response_code(405);
                            echo json_encode(["Error" => "Method not allowed"]);
                            break;
                    }
                }
                else if (count($path) == 2) {
                    switch($requestMethod) {
                        case "GET":
                            echo json_encode($this->currencyController->read($path[1]));
                            http_response_code(200);
                            break;
                        default:
                            http_response_code(405);
                            echo json_encode(["Error" => "Method not allowed"]);
                            break;
                    }
                }
            } catch (CurrencyNotFoundException $e) {
                http_response_code(404);
                echo json_encode(["Error" => $e->getMessage()]);
            }
            catch (CurrencyAlreadyExistsException $e) {
                http_response_code(409);
                echo json_encode(["Error" => $e->getMessage()]);
            }
            catch (MissingFieldException $e) {
                http_response_code(400);
                echo json_encode(["Error" => $e->getMessage()]);
            }
             catch (\Exception $e) {
                http_response_code(500);
                echo json_encode(["Error" => $e->getMessage()]);
            }
        }

        private function handleExchangeRates($requestMethod, $path) {
            try {
                if (count($path) == 1) {
                    switch($requestMethod) {
                        case "GET":
                            echo json_encode($this->exchangeRateController->read());
                            http_response_code(200);
                            break;
                        case "POST":
                            $result = $this->exchangeRateController->create();
                            http_response_code(201);
                            echo json_encode($result);
                            break;
                        default:
                            http_response_code(405);
                            echo json_encode(["Error"=> "Method not allowed"]);
                            break;
                    }
                }
                else if (count($path) == 2) {
                    switch($requestMethod) {
                        case "GET":
                            $baseId = $this->currencyController->getCurrencyIdByCode(substr($path[1], 0, 3));
                            $targetId = $this->currencyController->getCurrencyIdByCode(substr($path[1], 3, 3));
    
                            echo json_encode($this->exchangeRateController->read($baseId, $targetId));
                            http_response_code(200);
                            break;
                        case "PATCH":
                            $baseId = $this->currencyController->getCurrencyIdByCode(substr($path[1], 0, 3));
                            $targetId = $this->currencyController->getCurrencyIdByCode(substr($path[1], 3, 3));
                            $result = $this->exchangeRateController->update($baseId, $targetId);
                            http_response_code(200);
                            echo json_encode($result);
                            break;
                        default:
                            http_response_code(405);
                            echo json_encode(["Error"=> "Method not allowed"]);
                            break;
                    }
                }
            }
            catch (ExchangeRateNotFoundException $e) {
                http_response_code(404);
                echo json_encode(["Error" => $e->getMessage()]);
            }
            catch (\Exception $e) {
                http_response_code(500);
                echo json_encode(["Error" => $e->getMessage()]);
            }
            catch (\InvalidArgumentException $e) {
                http_response_code(400);
                echo json_encode(["Error" => $e->getMessage()]);
            }
        }

        private function handleExchange($requestMethod) {
            try {
                switch($requestMethod) {
                    case "GET":
                        if (isset($_GET['from']) && isset($_GET['to']) && isset($_GET['amount'])) {
                            $from = $_GET['from'];
                            $to = $_GET['to'];
                            $amount = $_GET['amount'];

                            if (strlen($from) !== 3 || strlen($to) !== 3 || !is_numeric($amount) || $amount <= 0) {
                                http_response_code(400);
                                echo json_encode(["message" => "Invalid input values"]);
                                return;
                            }
                            
                            http_response_code(200);
                            echo json_encode($this->exchangeRateController->convert($from, $to, $amount));
                        } else {
                            http_response_code(400);
                            echo json_encode(["Error" => "Missing required query parameters"]);
                        }
                        break;
                    default:
                        http_response_code(405);
                        echo json_encode(["Error"=> "Method not allowed"]);
                        break;
                }
            } catch (\Exception $e) {
                http_response_code(500);
                echo json_encode(["Error" => $e->getMessage()]);
            }
        }
        
    }
?>