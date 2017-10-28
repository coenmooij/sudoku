<?php

namespace CoenMooij\Sudoku;

use CoenMooij\Sudoku\Generator\PuzzleGenerator;
use CoenMooij\Sudoku\Generator\SolutionGenerator;
use CoenMooij\Sudoku\Serializer\GridSerializer;
use CoenMooij\Sudoku\Puzzle\Difficulty;
use CoenMooij\Sudoku\Puzzle\Location;
use CoenMooij\Sudoku\Puzzle\Puzzle;
use CoenMooij\Sudoku\Solver\BacktrackSolver;
use CoenMooij\Sudoku\Solver\SimpleSolver;
use CoenMooij\Sudoku\Validator\GridValidator;

class SudokuService
{
    /**
     * @var SimpleSolver
     */
    private $simpleSolver;
    /**
     * @var SolutionGenerator
     */
    private $solutionGenerator;
    /**
     * @var PuzzleGenerator
     */
    private $puzzleGenerator;
    /**
     * @var BacktrackSolver
     */
    private $backtrackSolver;

    public function __construct(
        SolutionGenerator $solutionGenerator,
        PuzzleGenerator $puzzleGenerator,
        BacktrackSolver $backtrackSolver,
        SimpleSolver $simpleSolver
    ) {
        $this->puzzleGenerator = $puzzleGenerator;
        $this->solutionGenerator = $solutionGenerator;
        $this->backtrackSolver = $backtrackSolver;
        $this->simpleSolver = $simpleSolver;
    }

    public function hint(Puzzle $puzzle): Location
    {
        return $this->simpleSolver->hint($puzzle->getGrid());
    }

    public function simpleSolve(Puzzle $puzzle): Puzzle
    {
        $grid = $this->simpleSolver->solve($puzzle->getGrid());
        $puzzle->setGrid($grid);

        return $puzzle;
    }

    public function solve(Puzzle $puzzle): Puzzle
    {
        $grid = $this->simpleSolver->solve($puzzle->getGrid());

        if ($grid->numberOfEmptyFields() > 0) {
            $grid = $this->backtrackSolver->solve($grid);
        }
        $puzzle->setGrid($grid);

        return $puzzle;
    }

    public function generatePuzzle(Difficulty $difficulty): Puzzle
    {
        $solution = $this->solutionGenerator->generateSolution();

        return $this->puzzleGenerator->generate($solution, $difficulty);
    }

    public function puzzleIsValid(Puzzle $puzzle): bool
    {
        GridValidator::gridIsValid($puzzle->getGrid());
    }
}
