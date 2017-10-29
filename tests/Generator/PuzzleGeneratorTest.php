<?php

namespace CoenMooij\Sudoku\Generator;

use CoenMooij\Sudoku\Puzzle\Difficulty;
use CoenMooij\Sudoku\Serializer\GridSerializer;
use CoenMooij\Sudoku\Solver\BacktrackSolver;
use CoenMooij\Sudoku\Validator\DigValidator;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class PuzzleGeneratorTest extends TestCase
{
    const SOLUTION_GRID = '642957138719843652538126794483712569976538421125694387294361875357489216861275943';

    /**
     * @var PuzzleGenerator
     */
    private $generator;

    /**
     * @var Difficulty|MockInterface
     */
    private $difficultyMock;

    public function setUp(): void
    {
        $backtrackSolver = new BacktrackSolver();
        $digValidator = new DigValidator($backtrackSolver);
        $this->generator = new PuzzleGenerator($digValidator);
        $this->difficultyMock = Mockery::mock(Difficulty::class);
    }

    /**
     * @test
     */
    public function generate_simple(): void
    {
        $solutionGrid = GridSerializer::deserialize(self::SOLUTION_GRID);
        self::assertEquals(0, $solutionGrid->numberOfEmptyFields());

        $this->difficultyMock->shouldReceive('getNumberOfHoles')->once()->andReturn(1);
        $this->difficultyMock->shouldReceive('getBound')->once()->andReturn(8);

        $puzzle = $this->generator->generate($solutionGrid, $this->difficultyMock);
        self::assertEquals(1, $puzzle->getGrid()->numberOfEmptyFields());
        self::assertCount(80, $puzzle->getPresetLocations());
    }

    /**
     * @test
     */
    public function generate_complicated(): void
    {
        $solutionGrid = GridSerializer::deserialize(self::SOLUTION_GRID);
        self::assertEquals(0, $solutionGrid->numberOfEmptyFields());

        $this->difficultyMock->shouldReceive('getNumberOfHoles')->once()->andReturn(81);
        $this->difficultyMock->shouldReceive('getBound')->once()->andReturn(1);

        $puzzle = $this->generator->generate($solutionGrid, $this->difficultyMock);
        self::assertGreaterThan(9, $puzzle->getGrid()->numberOfEmptyFields());
        self::assertGreaterThan(16, $puzzle->getPresetLocations());
    }
}
