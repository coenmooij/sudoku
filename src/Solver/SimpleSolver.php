<?php

declare(strict_types=1);

namespace CoenMooij\Sudoku\Solver;

use CoenMooij\Sudoku\Puzzle\Grid;
use CoenMooij\Sudoku\Puzzle\Location;

/**
 * Solves the given sudoku using only row, column, and block checks.
 * Class SimpleSolver
 */
class SimpleSolver implements SudokuSolverInterface
{
    public function solve(Grid $grid): Grid
    {
        for ($row = 0; $row < 9; $row++) {
            for ($column = 0; $column < 9; $column++) {
                $location = new Location($row, $column);
                if ($grid->getCellValue($location) === 0) {
                    $opportunities = $grid->possibilitiesForCell($location);
                    if (count($opportunities) === 1) {
                        $grid->setCell($location, $opportunities[0]);
                        $column = 0;
                        $row = 0;
                    }
                }
            }
        }

        return $grid;
    }
}
