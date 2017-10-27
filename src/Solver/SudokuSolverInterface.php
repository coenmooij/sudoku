<?php

namespace CoenMooij\Sudoku\Solver;

use CoenMooij\Sudoku\SudokuGrid;

/**
 * Interface SudokuSolver
 */
interface SudokuSolverInterface
{
    public function solve(SudokuGrid $sudokuGrid);
}
