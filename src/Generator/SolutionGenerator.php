<?php

declare(strict_types=1);

namespace CoenMooij\Sudoku\Generator;

use CoenMooij\Sudoku\Exception\UnsolvableException;
use CoenMooij\Sudoku\Puzzle\Grid;
use CoenMooij\Sudoku\Puzzle\Location;
use CoenMooij\Sudoku\SudokuValidator;

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
    private $grid;

    /**
     * @var SudokuValidator
     */
    private $validator;

    public function __construct(SudokuValidator $sudokuValidator)
    {
        $this->validator = $sudokuValidator;
    }

    public function generateSolution(): Grid
    {
        do {
            $this->grid = new Grid();
            $this->placeRandomStarters();
        } while (!$this->sudokuIsSolvable());

        return $this->grid;
    }

    private function placeRandomStarters(): void
    {
        for ($i = 0; $i < self::NUMBER_OF_RANDOM_STARTERS; $i++) {
            $location = $this->getRandomEmptyCell();
            do {
                $this->grid->setCell($location, random_int(1, 9));
            } while (!$this->isValid());
        }
    }

    private function isValid(): bool
    {
        return $this->validator->validate($this->grid);
    }

    /**
     * Returns a random currently empty cell.
     * @return array
     */
    private function getRandomEmptyCell(): Location
    {
        do {
            $location = new Location(random_int(0, 8), random_int(0, 8));
        } while ($this->grid->isEmpty($location));

        return $location;
    }

    private function sudokuIsSolvable(): bool
    {
        $solver = new BacktrackSolver();
        try {
            $solver->solve($this->grid);
            return true;
        } catch (UnsolvableException $exception) {
            return false;
        }
    }
}
