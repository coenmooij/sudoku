<?php

namespace CoenMooij\Sudoku\Puzzle;

/**
 * Class Puzzle
 */
class Puzzle
{
    /**
     * @var array
     */
    private $puzzle;

    public function __construct(Grid $sudokuGrid)
    {
        $this->setGrid($sudokuGrid);
    }

    /**
     * Getter for the puzzle.
     * @return array
     */
    public function getPuzzle()
    {
        return $this->puzzle;
    }

    /**
     * Fills the puzzle with given grid.
     *
     * @param Grid $grid The sudoku grid.
     *
     * @return void
     */
    private function setGrid(Grid $grid)
    {
        for ($row = 0; $row < 9; $row++) {
            for ($column = 0; $column < 9; $column++) {
                $value = $grid->getCellValue($row, $column);
                $this->puzzle[$row][$column] = [
                    'given' => $value > 0,
                    'value' => $value,
                ];
            }
        }
    }
}
