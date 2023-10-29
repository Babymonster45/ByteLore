<?php
use PHPUnit\Framework\TestCase;
// Min length 8
// At least 1 number
// At least 1 special character
// At least 1 upper case character

class CreatePasswordTest extends TestCase {
    public function testPasswordContainsOnlyAsciiCharactersInRange() {
        // Valid password with ASCII characters
        $validPassword = ['Password#@!', '$Password(?)', 'Password!123456608493'];

        // Invalid password
        $invalidPassword = ['Password Password', 'Test٩(͡๏̯͡๏)۶', 'hello', '12345678%'];

        foreach ($validPassword as $password) {
            $this->assertTrue($this->isValidPassword($password), "Password '$password' should be valid.");
        }

        foreach ($invalidPassword as $password) {
            $this->assertFalse($this->isValidPassword($password), "Password '$password' should be invalid.");
        }
    }

    private function isValidPassword($password) {

        if (mb_strlen($password, 'UTF-8') > 7 ){
            if (!preg_match('/^[A-Z]+$/', $password) && !preg_match('/^[0-9]+$/', $password) && !preg_match('/^[\x21\x23\x24\x26\x28-\x2B\x2D\x3D\x3F\x40\x5B\x7E]+$/', $password)){
                return preg_match('/^[\x21\x23\x24\x26\x28-\x2B\x2D\x30-\x39\x3D\x3F-\x5B\x5D-\x7A\x7E]+$/', $password) === 1;
                
            }
            return false;
        }
        return false;
    }
}
