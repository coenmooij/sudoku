<?php

namespace CoenMooij\Sudoku\Generator;

/**
 * Class SolutionGenerator
 */
class SolutionGenerator
{
    // todo make configurable
    const NUMBER_OF_RANDOM_STARTERS = 11;

    /**
     * @var Grid
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

    public function generateSolution(): Grid
    {
        do {
            $this->sudokuGrid = new Grid();
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
    private function getRandomEmptyCell(): Location
    {
        do {
            $location = new Location(random_int(0, 8), random_int(0, 8));
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
