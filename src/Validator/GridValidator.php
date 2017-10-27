<?php

declare(strict_types=1);

namespace CoenMooij\Sudoku\Validator;

use CoenMooij\Sudoku\Puzzle\Cell;
use CoenMooij\Sudoku\Puzzle\Grid;

/**
 * Class SudokuValidator
 */
final class GridValidator
{
    /**
     * @param Grid $grid
     *
     * @return bool
     */
    public static function gridIsValid(Grid $grid): bool
    {
        return self::columnsAreValid($grid) && self::rowsAreValid($grid) && self::blocksAreValid($grid);
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
    public static function blocksAreValid(Grid $grid): bool
    {
        foreach ($grid->getAllBlocks() as $block) {
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
        $values = array_diff($values, [Cell::EMPTY_VALUE]);

        return count(array_count_values($values)) !== count($values);
    }
}
