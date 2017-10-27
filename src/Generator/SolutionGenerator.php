<?php

declare(strict_types=1);

namespace CoenMooij\Sudoku\Generator;

use CoenMooij\Sudoku\Exception\UnsolvableException;
use CoenMooij\Sudoku\Puzzle\Grid;
use CoenMooij\Sudoku\Puzzle\Location;
use CoenMooij\Sudoku\Solver\BacktrackSolver;
use CoenMooij\Sudoku\Validator\GridValidator;

/**
 * Class SolutionGenerator
 */
final class SolutionGenerator
{
    const NUMBER_OF_RANDOM_STARTERS = 11;

    /**
     * @var Grid
     */
    private $grid;

    /**
     * @var BacktrackSolver
     */
    private $solver;

    /**
     * SolutionGenerator constructor.
     *
     * @param BacktrackSolver $solver
     */
    public function __construct(BacktrackSolver $solver)
    {
        $this->solver = $solver;
    }

    /**
     * @return Grid
     */
    public function generateSolution(): Grid
    {
        do {
            $this->grid = new Grid();
            $this->placeRandomStarters();
        } while (!$this->gridIsSolvable());

        return $this->grid;
    }

    /**
     * @return void
     */
    private function placeRandomStarters(): void
    {
        for ($i = 0; $i < self::NUMBER_OF_RANDOM_STARTERS; $i++) {
            $location = $this->getRandomEmptyLocation();
            do {
                $this->grid->setCell($location, random_int(1, 9));
            } while (!GridValidator::gridIsValid($this->grid));
        }
    }

    /**
     * @return Location
     */
    private function getRandomEmptyLocation(): Location
    {
        do {
            $location = new Location(random_int(0, 8), random_int(0, 8));
        } while (!$this->grid->getCell($location)->isEmpty());

        return $location;
    }

    /**
     * @return bool
     */
    private function gridIsSolvable(): bool
    {
        try {
            $this->solver->solve($this->grid);

            return true;
        } catch (UnsolvableException $exception) {
            return false;
        }
    }
}
