<?php

namespace CoenMooij\Sudoku\Generator;

use CoenMooij\Sudoku\Solver\BacktrackSolver;
use CoenMooij\Sudoku\Validator\GridValidator;
use PHPUnit\Framework\TestCase;

class SolutionGeneratorTest extends TestCase
{
    /**
     * @var SolutionGenerator
     */
    private $generator;

    public function setUp(): void
    {
        $backtrackSolver = new BacktrackSolver();
        $this->generator = new SolutionGenerator($backtrackSolver);
    }

    /**
     * @test
     */
    public function generate(): void
    {
        $grid = $this->generator->generate();
        self::assertEquals(0, $grid->numberOfEmptyFields());
        self::assertTrue(GridValidator::gridIsValid($grid));
    }
}
