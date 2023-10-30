<?php
use PHPUnit\Framework\TestCase;
// Min length 3
// Only set ascii characters allowed

class CreateUsernameTest extends TestCase {
    public function testUsernameContainsOnlyAsciiCharactersInRange() {
        $validUsernames = ['Megaman Rocks#', 'Hello123', 'ASCII123', 'hey'];

        $invalidUsernames = ['Mega❌man', 'Hello✓', 'Test٩(͡๏̯͡๏)۶', 'yo'];

        foreach ($validUsernames as $username) {
            $this->assertTrue($this->isValidUsername($username), "Username '$username' should be valid.");
        }

        foreach ($invalidUsernames as $username) {
            $this->assertFalse($this->isValidUsername($username), "Username '$username' should be invalid.");
        }
    }

    private function isValidUsername($username) {
        if (strlen($username) > 2 ){
            return preg_match('/^[\x20\x23\x2D\x2E\x30-\x39\x41-\x5A\x5F\x61-\x7A]+$/', $username) === 1;
        }
        return false;
    }
}