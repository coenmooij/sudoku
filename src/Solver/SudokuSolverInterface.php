<?php

namespace CoenMooij\Sudoku\Solver;

use CoenMooij\Sudoku\Puzzle\Grid;

/**
 * Interface SudokuSolverInterface
 */
interface SudokuSolverInterface
{
    public function solve(Grid $grid);
}
