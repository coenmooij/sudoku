<?php

declare(strict_types=1);

namespace CoenMooij\Sudoku\Parser;

use CoenMooij\Sudoku\Puzzle\Cell;
use CoenMooij\Sudoku\Puzzle\Grid;
use LengthException;

/**
 * Class GridSerializer
 */
final class GridSerializer
{
    /**
     * @param Grid $grid
     *
     * @return string
     */
    public static function serialize(Grid $grid): string
    {
        $string = '';
        for ($i = 0; $i < Grid::NUMBER_OF_CELLS; $i++) {
            $string .= (string) $grid->getCellValue(Grid::getLocationByIndex($i));
        }

        return $string;
    }

    /**
     * @param string $string
     *
     * @return Grid
     * @throws LengthException
     */
    public static function deserialize(string $string): Grid
    {
        if (strlen($string) !== Grid::NUMBER_OF_CELLS) {
            throw new LengthException();
        }
        $cells = [];

        for ($i = 0; $i < Grid::NUMBER_OF_CELLS; $i++) {
            $location = Grid::getLocationByIndex($i);
            $cells[] = new Cell($location, $string[$i]);
        }

        return new Grid($cells);
    }
}
