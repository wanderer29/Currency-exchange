<?php
    namespace Exceptions;

    class ElementNotFoundException extends \Exception {
        public function __construct($message = "Element not found", $code = 0, \Exception $previous = null) {
            parent::__construct($message, $code, $previous);
        }
    }

?>