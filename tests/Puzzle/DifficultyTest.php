<?php

namespace CoenMooij\Sudoku\Puzzle;

use CoenMooij\Sudoku\Exception\InvalidDifficultyException;
use PHPUnit\Framework\TestCase;

class DifficultyTest extends TestCase
{
    const INVALID_DIFFICULTY = 0;
    const NORMAL_NUMBER_OF_HOLES = 50;
    const NORMAL_BOUND = 3;

    /**
     * @test
     */
    public function construct(): void
    {
        $difficulty = new Difficulty(Difficulty::NORMAL);
        self::assertEquals(self::NORMAL_NUMBER_OF_HOLES, $difficulty->getNumberOfHoles());
        self::assertEquals(self::NORMAL_BOUND, $difficulty->getBound());
    }

    /**
     * @test
     */
    public function construct_invalid(): void
    {
        try {
            new Difficulty(self::INVALID_DIFFICULTY);
            self::assertTrue(false);
        } catch (InvalidDifficultyException $exception) {
            self::assertTrue(true);
        }
    }
}
