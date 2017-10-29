<?php

namespace CoenMooij\Sudoku;

use CoenMooij\Sudoku\Generator\HintGenerator;
use CoenMooij\Sudoku\Generator\PuzzleGenerator;
use CoenMooij\Sudoku\Generator\SolutionGenerator;
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
    /**
     * @var HintGenerator
     */
    private $hintGenerator;

    public function __construct(
        HintGenerator $hintGenerator,
        SolutionGenerator $solutionGenerator,
        PuzzleGenerator $puzzleGenerator,
        BacktrackSolver $backtrackSolver,
        SimpleSolver $simpleSolver
    ) {
        $this->hintGenerator = $hintGenerator;
        $this->puzzleGenerator = $puzzleGenerator;
        $this->solutionGenerator = $solutionGenerator;
        $this->backtrackSolver = $backtrackSolver;
        $this->simpleSolver = $simpleSolver;
    }

    public function getHint(Puzzle $puzzle): Location
    {
        return $this->hintGenerator->generateOne($puzzle->getGrid());
    }

    /**
     * @param Puzzle $puzzle
     *
     * @return Location[]
     */
    public function getHints(Puzzle $puzzle): array
    {
        return $this->hintGenerator->generateAll($puzzle->getGrid());
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
