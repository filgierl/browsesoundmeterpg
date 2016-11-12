<?php
/* 
    Author     : Daniel
*/
include_once 'function.php';
include_once 'errors.php';
class DataBaseManager{
    private $connection;
    private static $instance;
    private $HOST = "localhost";    
    private $USER = "sec_user";    
    private $PASSWORD = "eKcGZr59zAa2BEWU";    
    private $DATABASE =  "secure_login";
    
    public static function getInstance() {
        if(!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        try {
            $this->connection = new mysqli($this->HOST, $this->USER, $this->PASSWORD, $this->DATABASE);

            if(mysqli_connect_error()) {
                handleError("Can not connect to database", Errors::DATABASE_ERROR);
                exit();
            }
        } catch (Exception $e ) {
            if(mysqli_connect_error()) {
                handleError("Can not connect to database", Errors::DATABASE_ERROR);
                exit();
            }
        }
    }
    
    function __destruct() {
        if(isset($this->connection)){
            $this->connection->close();
        }
    }
    
    private function __clone() { }
    public function getConnection() {
         return $this->connection;
    }
}
