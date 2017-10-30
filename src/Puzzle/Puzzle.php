<?php

declare(strict_types=1);

namespace CoenMooij\Sudoku\Puzzle;

class Puzzle
{
    /**
     * @var Grid
     */
    private $grid;

    /**
     * @var Location[]
     */
    private $presetLocations;

    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
        $this->initializePresetLocations();
    }

    public function getGrid(): Grid
    {
        return $this->grid;
    }

    public function setGrid(Grid $grid): void
    {
        $this->grid = $grid;
    }

    /**
     * @return Location[]
     */
    public function getPresetLocations(): array
    {
        return $this->presetLocations;
    }

    private function initializePresetLocations(): void
    {
        for ($row = 0; $row < Grid::NUMBER_OF_ROWS; $row++) {
            for ($column = 0; $column < Grid::NUMBER_OF_COLUMNS; $column++) {
                $location = new Location($row, $column);
                if (!$this->grid->isEmpty($location)) {
                    $this->presetLocations[] = $location;
                }
            }
        }
    }
}
