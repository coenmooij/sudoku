<?php

declare(strict_types=1);

namespace CoenMooij\Sudoku\Generator;

use CoenMooij\Sudoku\Exception\UnsolvableException;
use CoenMooij\Sudoku\Puzzle\Grid;
use CoenMooij\Sudoku\Puzzle\Location;

class HintGenerator
{
    public function generateOne(Grid $grid): Location
    {
        foreach ($this->getLocations() as $location) {
            if ($this->hasOnePossibleValue($grid, $location)) {
                return $location;
            }
        }
        throw new UnsolvableException();
    }

    /**
     * @param Grid $grid
     *
     * @return Location[]
     */
    public function generateAll(Grid $grid): array
    {
        $locations = [];
        foreach ($this->getLocations() as $location) {
            if ($this->hasOnePossibleValue($grid, $location)) {
                $locations[] = $location;
            }
        }

        return $locations;
    }

    /**
     * @return Location[]
     */
    private function getLocations(): array
    {
        $locations = [];
        for ($row = 0; $row < Grid::NUMBER_OF_ROWS; $row++) {
            for ($column = 0; $column < Grid::NUMBER_OF_COLUMNS; $column++) {
                $locations[] = new Location($row, $column);
            }
        }

        return $locations;
    }

    private function hasOnePossibleValue(Grid $grid, Location $location): bool
    {
        $possibleValues = $grid->getAllPossibilitiesFor($location);

        return count($possibleValues) === 1;
    }
}
