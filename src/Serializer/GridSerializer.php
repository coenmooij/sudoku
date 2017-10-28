<?php

declare(strict_types=1);

namespace CoenMooij\Sudoku\Parser;

use CoenMooij\Sudoku\Puzzle\Grid;
use CoenMooij\Sudoku\Puzzle\Location;
use LengthException;

final class GridSerializer
{
    public static function serialize(Grid $grid): string
    {
        $string = '';
        for ($i = 0; $i < Grid::NUMBER_OF_LOCATIONS; $i++) {
            $string .= (string) $grid->get(self::getLocationByIndex($i));
        }

        return $string;
    }

    public static function deserialize(string $string): Grid
    {
        if (strlen($string) !== Grid::NUMBER_OF_LOCATIONS) {
            throw new LengthException();
        }
        $grid = new Grid();
        for ($i = 0; $i < Grid::NUMBER_OF_LOCATIONS; $i++) {
            $location = self::getLocationByIndex($i);
            $grid->set($location, $string[$i]);
        }

        return $grid;
    }

    public static function getLocationByIndex(int $index): Location
    {
        $row = (int) floor($index / Grid::NUMBER_OF_COLUMNS);
        $column = $index % Grid::NUMBER_OF_ROWS;

        return new Location($row, $column);
    }
}
