<?php
// Start a session to manage user login state
session_start();

// Check if the user is already logged in
if (isset($_SESSION["user_id"])) {
    header("Location: /");
    exit();
}

use PHPUnit\Framework\TestCase;
// Only ASCII characters in the range 32-126

class CreatePageTest extends TestCase {
    public function testTitleContainsOnlyAsciiCharactersInRange() {
        $validTitles = ['Megaman Rocks!', 'Hello123', 'ASCII123'];

        $invalidTitles = ['Mega❌man', 'Hello✓', 'Test٩(͡๏̯͡๏)۶'];

        foreach ($validTitles as $title) {
            $this->assertTrue($this->isValidTitle($title), "Title '$title' should be valid.");
        }

        foreach ($invalidTitles as $title) {
            $this->assertFalse($this->isValidTitle($title), "Title '$title' should be invalid.");
        }
    }

    private function isValidTitle($title) {
        return preg_match('/^[\x20-\x7E]+$/', $title) === 1;
    }
}
