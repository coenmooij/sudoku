<?php

namespace CoenMooij\Sudoku\Solver;

use CoenMooij\Sudoku\Location;
use CoenMooij\Sudoku\Grid;

/**
 * Solves the given sudoku using only row, column, and block checks.
 * Class SimpleSolver
 */
class SimpleSolver implements SudokuSolverInterface
{
    public function solve(Grid $sudokuGrid): Grid
    {
        for ($row = 0; $row < 9; $row++) {
            for ($column = 0; $column < 9; $column++) {
                $location = new Location($row, $column);
                if ($sudokuGrid->getCell($location) === 0) {
                    $opportunities = $sudokuGrid->possibilitiesForCell($location);
                    if (count($opportunities) === 1) {
                        $sudokuGrid->setCell($location, $opportunities[0]);
                        $column = 0;
                        $row = 0;
                    }
                }
            }
        }

        return $sudokuGrid;
    }
}
