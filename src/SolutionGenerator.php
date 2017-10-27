<?php

namespace CoenMooij\Sudoku;

/**
 * Class SolutionGenerator
 */
class SolutionGenerator
{
    // todo make configurable
    const NUMBER_OF_RANDOM_STARTERS = 11;

    /**
     * @var SudokuGrid
     */
    private $sudokuGrid;

    /**
     * @var SudokuValidator
     */
    private $validator;

    // todo proper dependency injection
    public function __construct()
    {
        $this->validator = new SudokuValidator();
    }

    public function generateSolution(): SudokuGrid
    {
        do {
            $this->sudokuGrid = new SudokuGrid();
            $this->placeRandomStarters();
        } while (!$this->sudokuIsSolvable());

        return $this->sudokuGrid;
    }

    private function placeRandomStarters(): void
    {
        for ($i = 0; $i < self::NUMBER_OF_RANDOM_STARTERS; $i++) {
            $location = $this->getRandomEmptyCell();
            do {
                $this->sudokuGrid->setCell($location, random_int(1, 9));
            } while (!$this->isValid());
        }
    }

    private function isValid(): bool
    {
        return $this->validator->validate($this->sudokuGrid);
    }

    /**
     * Returns a random currently empty cell.
     * @return array
     */
    private function getRandomEmptyCell(): GridLocation
    {
        do {
            $location = new GridLocation(random_int(0, 8), random_int(0, 8));
        } while ($this->sudokuGrid->isEmpty($location));

        return $location;
    }

    private function sudokuIsSolvable(): bool
    {
        $solver = new BacktrackSolver();
        try {
            $solver->solve($this->sudokuGrid);

            return true;
        } catch (UnsolvableException $exception) {
            return false;
        }
    }
}
