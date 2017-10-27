<?php

namespace CoenMooij\Sudoku;

/**
 * Class SudokuParser
 *
 */
class SudokuParser
{
    /**
     * Parses the given sudoku string into a sudokuGrid object.
     *
     * @param string $string The sudoku in string form.
     *
     * @return SudokuGrid
     */
    public function parse(string $string)
    {
        $grid = [];
        for ($i = 0; $i < 9; $i++) {
            $grid[$i] = $this->parseRow(substr($string, $i * 9, 9));
        }
        $sudokuGrid = new SudokuGrid();
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
