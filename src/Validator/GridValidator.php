<?php

declare(strict_types=1);

namespace CoenMooij\Sudoku\Validator;

use CoenMooij\Sudoku\Puzzle\Grid;

class GridValidator
{
    public static function gridIsValid(Grid $grid): bool
    {
        return self::columnsAreValid($grid) && self::rowsAreValid($grid) && self::blocksAreValid($grid);
    }

    private static function rowsAreValid(Grid $grid): bool
    {
        for ($i = 0; $i < Grid::NUMBER_OF_ROWS; $i++) {
            $row = $grid->getRow($i);
            if (self::hasDuplicates($row)) {
                return false;
            }
        }

        return true;
    }

    private static function columnsAreValid(Grid $grid): bool
    {
        for ($i = 0; $i < Grid::NUMBER_OF_COLUMNS; $i++) {
            if (self::hasDuplicates($grid->getColumn($i))) {
                return false;
            }
        }

        return true;
    }

    private static function blocksAreValid(Grid $grid): bool
    {
        foreach ($grid->getBlocks() as $block) {
            if (self::hasDuplicates($block)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param int[] $values
     *
     * @return bool
     */
    private static function hasDuplicates(array $values): bool
    {
        $values = array_diff($values, [Grid::EMPTY_VALUE]);

        return count(array_count_values($values)) !== count($values);
    }
}
