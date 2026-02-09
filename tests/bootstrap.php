<?php
/**
 * PHPUnit Bootstrap - Test Configuration
 * Initializes the test environment for AMVRS ARMED
 */

// Define root
define('APP_ROOT', dirname(dirname(__FILE__)));

// Error reporting for tests
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Simple mock of database for testing
class MockDatabase {
    public $insert_id = 0;
    
    public function prepare($sql) {
        return new MockStatement($sql);
    }
}

class MockStatement {
    public $sql;
    public $params = array();
    public function __construct($sql) {
        $this->sql = $sql;
    }
    
    public function bind_param($types, &...$args) {
        $this->params = $args;
        return true;
    }
    
    public function execute() {
        return true;
    }
    
    public function get_result() {
        return new MockResult();
    }
    
    public $affected_rows = 0;
    public $error = '';
}

class MockResult {
    public $num_rows = 0;
    
    public function fetch_assoc() {
        return null;
    }
}

?>
