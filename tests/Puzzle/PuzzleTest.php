<?php

namespace CoenMooij\Sudoku\Puzzle;

use CoenMooij\Sudoku\Serializer\GridSerializer;
use PHPUnit\Framework\TestCase;

class PuzzleTest extends TestCase
{
    private const GRID = '004060002805002030000030060028000000000004000700050009002001000070040950600000040';

    /**
     * @test
     */
    public function initialize(): void
    {
        $grid = GridSerializer::deserialize(self::GRID);
        $numberOfEmptyValues = $grid->numberOfEmptyValues();
        $numberOfPresetValues = 81 - $numberOfEmptyValues;
        $puzzle = new Puzzle($grid);
        self::assertCount($numberOfPresetValues, $puzzle->getPresetLocations());
    }
}
