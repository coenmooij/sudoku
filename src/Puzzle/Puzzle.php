<?php

declare(strict_types=1);

namespace CoenMooij\Sudoku\Puzzle;

/**
 * Class Puzzle
 */
final class Puzzle
{
    /**
     * @var Grid
     */
    private $grid;
    /**
     * @var Cell[]
     */
    private $givenCells;

    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
        $this->givenCells = $this->grid->getAllFilledCells();
    }

    /**
     * @return Grid
     */
    public function getGrid(): Grid
    {
        return $this->grid;
    }

    /**
     * @param Grid $grid
     */
    public function setGrid(Grid $grid): void
    {
        $this->grid = $grid;
    }

    /**
     * @return Cell[]
     */
    public function getGivenCells(): array
    {
        return $this->givenCells;
    }
}
