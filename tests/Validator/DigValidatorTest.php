<?php

namespace CoenMooij\Sudoku\Validator;

use CoenMooij\Sudoku\Puzzle\Location;
use CoenMooij\Sudoku\Serializer\GridSerializer;
use CoenMooij\Sudoku\Solver\BacktrackSolver;
use PHPUnit\Framework\TestCase;

class DigValidatorTest extends TestCase
{
    private const FULL_GRID = '642957138719843652538126794483712569976538421125694387294361875357489216861275943';

    /**
     * @var DigValidator
     */
    private $validator;

    public function setUp(): void
    {
        $backtrackSolver = new BacktrackSolver();
        $this->validator = new DigValidator($backtrackSolver);
    }

    /**
     * @test
     */
    public function gridIsValid_success(): void
    {
        $grid = GridSerializer::deserialize(self::FULL_GRID);
        $location = new Location(4, 5);
        self::assertTrue($this->validator->isDiggableAndUniquelySolvableAfterDigging($grid, $location, 8));
    }

    /**
     * @test
     */
    public function gridIsValid_failure_highBound(): void
    {
        $location = new Location(0, 0);
        $grid = GridSerializer::deserialize(self::FULL_GRID);
        self::assertFalse($this->validator->isDiggableAndUniquelySolvableAfterDigging($grid, $location, 9));
    }

    /**
     * @test
     */
    public function gridIsValid_failure_emptyValue(): void
    {
        $location = new Location(0, 0);
        $grid = GridSerializer::deserialize(self::FULL_GRID);
        $grid->empty($location);
        self::assertFalse($this->validator->isDiggableAndUniquelySolvableAfterDigging($grid, $location, 0));
    }
}
