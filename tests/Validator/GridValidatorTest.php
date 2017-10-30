<?php

namespace CoenMooij\Sudoku\Validator;

use CoenMooij\Sudoku\Puzzle\Grid;
use CoenMooij\Sudoku\Puzzle\Location;
use PHPUnit\Framework\TestCase;

class GridValidatorTest extends TestCase
{
    /**
     * @test
     */
    public function gridIsValid_valid(): void
    {
        $grid = new Grid();
        self::assertTrue(GridValidator::gridIsValid($grid));

        $grid->set(new Location(0, 0), 1);
        self::assertTrue(GridValidator::gridIsValid($grid));

        $grid->set(new Location(0, 1), 2);
        $grid->set(new Location(3, 0), 2);
        self::assertTrue(GridValidator::gridIsValid($grid));
    }

    /**
     * @test
     */
    public function gridIsValid_invalidRow(): void
    {
        $grid = new Grid();

        $grid->set(new Location(0, 0), 1);
        $grid->set(new Location(0, 8), 1);
        self::assertFalse(GridValidator::gridIsValid($grid));
    }

    /**
     * @test
     */
    public function gridIsValid_invalidColumn(): void
    {
        $grid = new Grid();

        $grid->set(new Location(0, 0), 1);
        $grid->set(new Location(8, 0), 1);
        self::assertFalse(GridValidator::gridIsValid($grid));
    }

    /**
     * @test
     */
    public function gridIsValid_invalidBlock(): void
    {
        $grid = new Grid();

        $grid->set(new Location(0, 0), 1);
        $grid->set(new Location(1, 1), 1);
        self::assertFalse(GridValidator::gridIsValid($grid));
    }
}
