<?php

namespace CoenMooij\Sudoku;

/**
 * Class SudokuValidator
 */
class SudokuValidator
{
    /**
     * The sudokuGrid.
     * @var Grid
     */
    private $sudokuGrid;

    /**
     * Validates a sudoku grid. Checks if no invalid fields are filled.
     *
     * @param Grid $sudokuGrid The sudoku to be validated.
     *
     * @return boolean
     */
    public function validate(Grid $sudokuGrid): bool
    {
        $this->sudokuGrid = $sudokuGrid;

        return $this->validateColumns() && $this->validateRows() && $this->validateBlocks();
    }

    /**
     * Counts and returns the number of empty ('0') fields.
     *
     * @param Grid $sudokuGrid The sudoku grid.
     *
     * @return integer
     */
    public function numberOfEmptyFields(Grid $sudokuGrid)
    {
        $gridAsString = $sudokuGrid->getGridAsString();

        return substr_count($gridAsString, '0');
    }

    /**
     * Validates all rows.
     * @return boolean
     */
    private function validateRows()
    {
        for ($i = 0; $i < 9; $i++) {
            $row = $this->sudokuGrid->getRow($i);
            if (!$this->validateSet($row)) {
                return false; // Returns as soon as any set fails to improve performance
            }
        }

        return true;
    }

    /**
     * Validates all columns.
     * @return boolean
     */
    private function validateColumns()
    {
        for ($i = 0; $i < 9; $i++) {
            $column = $this->sudokuGrid->getColumn($i);
            if (!$this->validateSet($column)) {
                return false; // Returns as soon as any set fails to improve performance
            }
        }

        return true;
    }

    /**
     * Validates all 3x3 blocks in the grid.
     * @return boolean
     */
    private function validateBlocks()
    {
        for ($i = 0; $i < 9; $i++) {
            $block = $this->sudokuGrid->getBlockByNumber($i);
            if (!$this->validateSet($block)) {
                return false; // Returns as soon as any set fails to improve performance
            }
        }

        return true;
    }

    /**
     * Validates a set, checks whether there are no duplicates (except 0 = empty)
     *
     * @param array $set The set of numbers to be validated.
     *
     * @return boolean
     */
    private function validateSet(array $set)
    {
        $set = array_diff($set, [0]);
        if (count(array_unique($set)) < count($set)) {
            $response = false;
        } else {
            $response = true;
        }

        return $response;
    }
}
