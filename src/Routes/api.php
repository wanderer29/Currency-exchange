<?php
    namespace Routes;

    require_once __DIR__ . '/../Controllers/CurrencyController.php';
    require_once __DIR__ . '/../Controllers/ExchangeRateController.php';
    require_once __DIR__ . '/../Config/Database.php';

    use Config\Database;
    use Controllers\CurrencyController;
    use Controllers\ExchangeRateController;

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
            if (count($path) == 1) {
                switch($requestMethod) {
                    case "GET": 
                        echo json_encode($this->currencyController->read());
                        break;
                    case "POST":
                        $result = $this->currencyController->create();
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
                        if ($path[1]) {
                            echo json_encode($this->currencyController->read($path[1]));
                        }
                        else {
                            http_response_code(400);
                            echo "Missing currency code";    
                        }
                        break;
                    default:
                        http_response_code(405);
                        echo json_encode(["Error" => "Method not allowed"]);
                        break;
                }
            }
        }

        private function handleExchangeRates($requestMethod, $path) {
            if (count($path) == 1) {
                switch($requestMethod) {
                    case "GET":
                        echo json_encode($this->exchangeRateController->read());
                        break;
                    case "POST":
                        $result = $this->exchangeRateController->create();
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
                        break;
                    case "PATCH":
                        $baseId = $this->currencyController->getCurrencyIdByCode(substr($path[1], 0, 3));
                        $targetId = $this->currencyController->getCurrencyIdByCode(substr($path[1], 3, 3));
                        $result = $this->exchangeRateController->update($baseId, $targetId);
                        echo json_encode($result);
                        break;
                    default:
                        http_response_code(405);
                        echo json_encode(["Error"=> "Method not allowed"]);
                        break;
                }
            }
            else {
                http_response_code(405);
                echo json_encode(["Error" => "Endpoint not allowed"]);
                   
            }
        }

        private function handleExchange($requestMethod) {
            switch($requestMethod) {
                case "GET":
                    if (isset($_GET['from']) && isset($_GET['to']) && isset($_GET['amount'])) {
                        $from = $_GET['from'];
                        $to = $_GET['to'];
                        $amount = $_GET['amount'];
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
        }
        
    }
?>