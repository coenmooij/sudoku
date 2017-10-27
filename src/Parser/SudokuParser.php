<?php

namespace CoenMooij\Sudoku;

use CoenMooij\Sudoku\Grid\Grid;

/**
 * Class SudokuParser
 *
 */
class SudokuParser
{
    /**
     * @param string $string
     *
     * @return Grid
     */
    public function parse(string $string): Grid
    {
        $grid = [];
        for ($i = 0; $i < 9; $i++) {
            $grid[$i] = $this->parseRow(substr($string, $i * 9, 9));
        }
        $sudokuGrid = new Grid();
        $sudokuGrid->setGrid($grid);
        return $sudokuGrid;
    }

    /**
     * Parses a single row into an array of integers.
     *
     * @param string $string The row in string form.
     *
     * @return array
     */
    private function parseRow($string)
    {
        $row = [];
        for ($column = 0; $column < 9; $column++) {
            $row[$column] = (int) substr($string, $column, 1);
        }
        return $row;
    }
}
