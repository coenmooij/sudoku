<?php

declare(strict_types=1);

namespace CoenMooij\Sudoku\Solver;

use CoenMooij\Sudoku\Exception\UnsolvableException;
use CoenMooij\Sudoku\Puzzle\Grid;
use CoenMooij\Sudoku\Puzzle\Location;

/**
 * Solves the given grid using only row, column, and block checks.
 */
class SimpleSolver implements GridSolverInterface
{
    /**
     * @var Grid
     */
    private $grid;

    public function solve(Grid $grid): Grid
    {
        $this->grid = $grid;
        for ($row = 0; $row < Grid::NUMBER_OF_ROWS; $row++) {
            for ($column = 0; $column < Grid::NUMBER_OF_COLUMNS; $column++) {
                $location = new Location($row, $column);
                if ($this->grid->isEmpty($location)) {
                    $possibleValues = $this->grid->getAllPossibilitiesFor($location);
                    if (count($possibleValues) === 1) {
                        $grid->set($location, $possibleValues[$row][$column][0]);
                        $column = 0;
                        $row = 0;
                    }
                }
            }
        }

        return $grid;
    }

    public function hint(Grid $grid): Location
    {
        $this->grid = $grid;
        for ($row = 0; $row < Grid::NUMBER_OF_ROWS; $row++) {
            for ($column = 0; $column < Grid::NUMBER_OF_COLUMNS; $column++) {
                $location = new Location($row, $column);
                if ($this->hasOnePossibleValue($location)) {
                    return $location;
                }
            }
        }
        throw new UnsolvableException();
    }

    public function hasOnePossibleValue(Location $location): bool
    {
        $possibleValues = $this->grid->getAllPossibilitiesFor($location);

        return count($possibleValues) === 1;
    }
}