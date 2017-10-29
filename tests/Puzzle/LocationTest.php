<?php

namespace CoenMooij\Sudoku\Puzzle;

use PHPUnit\Framework\TestCase;

class LocationTest extends TestCase
{
    /**
     * @test
     */
    public function match_valid(): void
    {
        $location1 = new Location(4, 6);
        $location2 = new Location(4, 6);

        self::assertTrue(Location::match($location1, $location2));
    }

    /**
     * @test
     */
    public function match_invalid(): void
    {
        $location1 = new Location(6, 4);
        $location2 = new Location(4, 6);

        self::assertFalse(Location::match($location1, $location2));
    }
}
