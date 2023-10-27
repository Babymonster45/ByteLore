<?php
use PHPUnit\Framework\TestCase;

class CreatePageTest extends TestCase
{
    public function testTitleContainsOnlyAsciiCharactersInRange()
    {
        // Valid titles with ASCII characters in the range 33-126
        $validTitles = ['Megaman Rocks!', 'Hello123', 'ASCII123'];

        // Invalid titles with non-ASCII characters
        $invalidTitles = ['Mega❌man', 'Hello✓', 'Test٩(͡๏̯͡๏)۶'];

        foreach ($validTitles as $title) {
            $this->assertTrue($this->isValidTitle($title), "Title '$title' should be valid.");
        }

        foreach ($invalidTitles as $title) {
            $this->assertFalse($this->isValidTitle($title), "Title '$title' should be invalid.");
        }
    }

    private function isValidTitle($title)
    {
        return preg_match('/^[\x20-\x7E]+$/', $title) === 1;
    }
}
