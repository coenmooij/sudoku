<?php

namespace CoenMooij\Sudoku\Solver;

use CoenMooij\Sudoku\Serializer\GridSerializer;
use CoenMooij\Sudoku\Validator\GridValidator;
use PHPUnit\Framework\TestCase;

final class SimpleSolverTest extends TestCase
{
    private const EASY = '820345701194728605753190402001879023289050106037610840068901057975200310000087964';
    /**
     * @var SimpleSolver
     */
    private $simpleSolver;

    public function setUp(): void
    {
        $this->simpleSolver = new SimpleSolver();
    }

    /**
     * @test
     */
    public function solve(): void
    {
        $grid = GridSerializer::deserialize(self::EASY);
        $solvedGrid = $this->simpleSolver->solve($grid);

        self::assertTrue(GridValidator::gridIsValid($solvedGrid));
        self::assertEquals(0, $solvedGrid->numberOfEmptyValues());
    }
}
