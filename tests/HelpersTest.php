<?php
/**
 * Tests for Helper Functions
 * PHPUnit tests for input validation and security functions
 */

require_once __DIR__ . '/../helpers.php';

class HelpersTest extends \PHPUnit\Framework\TestCase {
    
    /**
     * Test email validation
     */
    public function testValidEmail() {
        $this->assertEquals('test@example.com', validate_email('test@example.com'));
        $this->assertEquals('', validate_email('invalid-email'));
        $this->assertEquals('', validate_email('not an email'));
    }
    
    /**
     * Test username validation
     */
    public function testValidateUsername() {
        $this->assertEquals('user123', validate_username('user123'));
        $this->assertEquals('test_user', validate_username('test_user'));
        $this->assertEquals('', validate_username('us'));  // Too short
        $this->assertEquals('', validate_username('user@name'));  // Invalid char
    }
    
    /**
     * Test password validation
     */
    public function testValidatePassword() {
        $this->assertTrue(validate_password('SecurePass123'));
        $this->assertFalse(validate_password('weak'));  // Too short
        $this->assertFalse(validate_password('nouppercase123'));  // No uppercase
        $this->assertFalse(validate_password('NOLOWERCASE123'));  // No lowercase
        $this->assertFalse(validate_password('NoNumbers'));  // No digits
    }
    
    /**
     * Test string validation
     */
    public function testValidateString() {
        $this->assertEquals('hello', validate_string('hello'));
        $this->assertNotEmpty(validate_string('   spaces   '));  // Should trim
        $this->assertEquals('', validate_string(array()));  // Not a string
        $this->assertEquals('', validate_string('x', 5));  // Over maxlen
    }
    
    /**
     * Test integer validation
     */
    public function testValidateInt() {
        $this->assertEquals(123, validate_int('123'));
        $this->assertEquals(0, validate_int('not int'));
        $this->assertEquals(456, validate_int(456));
    }
    
    /**
     * Test phone validation
     */
    public function testValidatePhone() {
        $this->assertNotEmpty(validate_phone('555-1234'));
        $this->assertNotEmpty(validate_phone('+1 (555) 123-4567'));
        $this->assertEquals('', validate_phone('abc'));  // Invalid
    }
    
    /**
     * Test flash messages
     */
    public function testFlashMessages() {
        // Start session if needed
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        set_flash('success', 'Operation successful');
        set_flash('error', 'An error occurred');
        
        $messages = get_flash();
        $this->assertCount(2, $messages);
        $this->assertEquals('success', $messages[0]['type']);
        
        // Check that flash is cleared after retrieval
        $messages2 = get_flash();
        $this->assertCount(0, $messages2);
    }
}

?>
