<?php
    abstract class Record {
        protected $db;
        protected $table;
        public function __construct($db) {
            $this->db = $db;
        }
    } 
?>