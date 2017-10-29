<?php

namespace CoenMooij\Sudoku\Solver;

use CoenMooij\Sudoku\Serializer\GridSerializer;
use CoenMooij\Sudoku\Validator\GridValidator;
use PHPUnit\Framework\TestCase;

final class BacktrackSolverTest extends TestCase
{
    private const VERY_HARD_SUDOKU_AS_STRING = '004060002805002030000030060028000000000004000700050009002001000070040950600000040';
    /**
     * @var BacktrackSolver
     */
    private $backtrackSolver;

    public function setUp(): void
    {
        $this->backtrackSolver = new BacktrackSolver();
    }

    /**
     * @test
     */
    public function solve(): void
    {
        $grid = GridSerializer::deserialize(self::VERY_HARD_SUDOKU_AS_STRING);
        $solvedGrid = $this->backtrackSolver->solve($grid);

        self::assertTrue(GridValidator::gridIsValid($solvedGrid));
        self::assertEquals(0, $solvedGrid->numberOfEmptyValues());
    }
}
