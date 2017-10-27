<?php

namespace CoenMooij\Sudoku;

/**
 * Class SudokuPuzzle
 */
class SudokuPuzzle
{
    /**
     * The puzzle.
     *
     * @var array
     */
    private $puzzle;

    public function __construct(SudokuGrid $sudokuGrid)
    {
        $this->setGrid($sudokuGrid);
    }

    /**
     * Getter for the puzzle.
     *
     * @return array
     */
    public function getPuzzle()
    {
        return $this->puzzle;
    }

    /**
     * Fills the puzzle with given grid.
     *
     * @param SudokuGrid $sudokuGrid The sudoku grid.
     *
     * @return void
     */
    private function setGrid(SudokuGrid $sudokuGrid)
    {
        for ($row = 0; $row < 9; $row++) {
            for ($column = 0; $column < 9; $column++) {
                $value = $sudokuGrid->getCell($row, $column);
                $this->puzzle[$row][$column] = [
                    'given' => $value > 0,
                    'value' => $value,
                ];
            }
        }
    }
}
