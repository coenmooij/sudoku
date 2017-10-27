<?php

namespace CoenMooij\Sudoku\Solver;

use CoenMooij\Sudoku\Grid;

/**
 * Interface SudokuSolver
 */
interface SudokuSolverInterface
{
    public function solve(Grid $sudokuGrid);
}
