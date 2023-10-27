<?php
// Title ascii test

use PHPUnit\Framework\TestCase;

class CreatePageTest extends TestCase
{
    public function testPageTitleContainsOnlyAsciiCharactersInRange()
    {
        // Valid title with ASCII characters in the range 33-126
        $validTitle = 'Megaman Rocks!';

        // Invalid title with non-ASCII character
        $invalidTitle = 'Mega❌man';

        $this->assertTrue($this->isValidTitle($validTitle));
        $this->assertFalse($this->isValidTitle($invalidTitle));
    }

    private function isValidTitle($title)
    {
        return preg_match('/^[\x21-\x7E]+$/', $title) === 1;
    }
}

?>