<?php

namespace CoenMooij\Sudoku;

use CoenMooij\Sudoku\Generator\HintGenerator;
use CoenMooij\Sudoku\Generator\PuzzleGenerator;
use CoenMooij\Sudoku\Generator\SolutionGenerator;
use CoenMooij\Sudoku\Puzzle\Grid;
use CoenMooij\Sudoku\Puzzle\Location;
use CoenMooij\Sudoku\Puzzle\Puzzle;
use CoenMooij\Sudoku\Solver\BacktrackSolver;
use CoenMooij\Sudoku\Solver\SimpleSolver;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class PuzzleServiceTest extends TestCase
{
    /**
     * @var PuzzleService
     */
    private $service;

    /**
     * @var HintGenerator|MockInterface
     */
    private $hintGeneratorMock;

    /**
     * @var PuzzleGenerator|MockInterface
     */
    private $puzzleGeneratorMock;

    /**
     * @var SolutionGenerator|MockInterface
     */
    private $solutionGeneratorMock;

    /**
     * @var BacktrackSolver|MockInterface
     */
    private $backtrackSolverMock;

    /**
     * @var SimpleSolver|MockInterface
     */
    private $simpleSolverMock;

    /**
     * @var Puzzle
     */
    private $puzzle;

    public function setUp(): void
    {
        $this->hintGeneratorMock = Mockery::mock(HintGenerator::class);
        $this->solutionGeneratorMock = Mockery::mock(SolutionGenerator::class);
        $this->puzzleGeneratorMock = Mockery::mock(PuzzleGenerator::class);
        $this->backtrackSolverMock = Mockery::mock(BacktrackSolver::class);
        $this->simpleSolverMock = Mockery::mock(SimpleSolver::class);
        $this->service = new PuzzleService(
            $this->hintGeneratorMock,
            $this->solutionGeneratorMock,
            $this->puzzleGeneratorMock,
            $this->backtrackSolverMock,
            $this->simpleSolverMock
        );

        $grid = new Grid();
        $this->puzzle = new Puzzle($grid);
    }

    /**
     * @test
     */
    public function getHint(): void
    {
        $location = new Location(0, 0);
        $this->hintGeneratorMock->shouldReceive('generateOne')->once()->andReturn($location);
        $hint = $this->service->getHint($this->puzzle);

        self::assertTrue(Location::match($location, $hint));
    }

    /**
     * @test
     */
    public function getHints(): void
    {
        $locationList = [
            new Location(0, 0),
            new Location(4, 6),
            new Location(7, 2)
        ];
        $this->hintGeneratorMock->shouldReceive('generateAll')->once()->andReturn($locationList);
        $hints = $this->service->getHints($this->puzzle);

        self::assertEquals($locationList, $hints);
    }
}
