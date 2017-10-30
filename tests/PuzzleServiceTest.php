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
    }

    /**
     * @test
     */
    public function getHint(): void
    {
        $grid = new Grid();
        $puzzle = new Puzzle($grid);
        $location = new Location(0, 0);
        $this->hintGeneratorMock->shouldReceive('generateOne')->once()->andReturn($location);
        $hint = $this->service->getHint($puzzle);

        self::assertTrue(Location::match($location, $hint));
    }
}
