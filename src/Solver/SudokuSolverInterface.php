<?php

declare(strict_types=1);

namespace CoenMooij\Sudoku\Solver;

use CoenMooij\Sudoku\Puzzle\Grid;

/**
 * Interface SudokuSolverInterface
 */
interface SudokuSolverInterface
{
    public function solve(Grid $grid);
}
