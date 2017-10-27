<?php

declare(strict_types=1);

namespace CoenMooij\Sudoku\Validator;

use CoenMooij\Sudoku\Puzzle\Grid;

/**
 * Class SudokuValidator
 */
final class SudokuValidator
{
    const ALL_VALID_VALUES = [1, 2, 3, 4, 5, 6, 7, 8, 9];

    /**
     * @param Grid $grid
     *
     * @return bool
     */
    public static function validate(Grid $grid): bool
    {
        return self::columnsAreValid($grid) && self::rowsAreValid($grid) && self::blocksAreValid($grid);
    }

    /**
     * @param int $value
     *
     * @return bool
     */
    public static function valueIsValid(int $value): bool
    {
    }

    /**
     * @param Grid $grid
     *
     * @return bool
     */
    public static function rowsAreValid(Grid $grid): bool
    {
        for ($i = 0; $i < Grid::NUMBER_OF_ROWS; $i++) {
            $row = $grid->getRow($i);
            if (self::hasDuplicates($row)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param Grid $grid
     *
     * @return bool
     */
    public static function columnsAreValid(Grid $grid): bool
    {
        for ($i = 0; $i < Grid::NUMBER_OF_COLUMNS; $i++) {
            if (self::hasDuplicates($grid->getColumn($i))) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param Grid $grid
     *
     * @return bool
     */
    public function blocksAreValid(Grid $grid): bool
    {
        for ($i = 0; $i < Grid::NUMBER_OF_BLOCKS; $i++) {
            $block = $grid->getBlockByNumber($i);
            if (self::hasDuplicates($block)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $values
     *
     * @return bool
     */
    private static function hasDuplicates(array $values): bool
    {
        $values = array_diff($values, [Grid::EMPTY_CELL]);

        return count(array_count_values($values)) !== count($values);
    }
}
