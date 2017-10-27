<?php

declare(strict_types=1);

namespace CoenMooij\Sudoku\Validator;

use CoenMooij\Sudoku\Exception\UnsolvableException;
use CoenMooij\Sudoku\Puzzle\Cell;
use CoenMooij\Sudoku\Puzzle\Grid;
use CoenMooij\Sudoku\Puzzle\Location;
use CoenMooij\Sudoku\Solver\BacktrackSolver;

/**
 * Class DigValidator
 */
class DigValidator
{
    /**
     * @var BacktrackSolver
     */
    private $solver;

    /**
     * DigValidator constructor.
     *
     * @param BacktrackSolver $solver
     */
    public function __construct(BacktrackSolver $solver)
    {
        $this->solver = $solver;
    }

    /**
     * @param Grid $sudokuGrid
     * @param Location $location
     * @param int $bound
     *
     * @return bool
     */
    public function isDiggableAndUniquelySolvableAfterDigging(
        Grid $sudokuGrid,
        Location $location,
        int $bound
    ): bool {
        return $this->cellIsDiggable($sudokuGrid, $location, $bound)
            && $this->cellIsUniquelySolvableAfterDigging($sudokuGrid, $location);
    }

    /**
     * @param Grid $grid
     * @param Location $location
     * @param int $bound
     *
     * @return bool
     */
    private function cellIsDiggable(Grid $grid, Location $location, int $bound): bool
    {
        return $bound <= 0 || (
                $this->rowIsDiggable($grid, $location, $bound)
                && $this->columnIsDiggable($grid, $location, $bound)
                && $this->blockIsDiggable($grid, $location, $bound)
            );
    }

    /**
     * @param Grid $grid
     * @param Location $location
     *
     * @return bool
     */
    private function cellIsUniquelySolvableAfterDigging(Grid $grid, Location $location): bool
    {
        $originalValue = $grid->getCellValue($location);
        $gridCopy = clone $grid;
        $gridCopy->emptyCell($location);
        $possibilities = $gridCopy->possibilitiesForCell($location);
        if (count($possibilities) > 1) {
            $possibilities = array_diff($possibilities, [$originalValue]);
            foreach ($possibilities as $possibility) {
                $gridCopy->setCell($location, $possibility);
                try {
                    $this->solver->solve($gridCopy);
                } catch (UnsolvableException $exception) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param Grid $grid
     * @param Location $location
     * @param int $bound
     *
     * @return bool
     */
    private function rowIsDiggable(Grid $grid, Location $location, int $bound): bool
    {
        $row = $grid->getRow($location->getRow());

        return $this->sectionIsDiggable($row, $bound);
    }

    /**
     * @param Grid $grid
     * @param Location $location
     * @param int $bound
     *
     * @return bool
     */
    private function columnIsDiggable(Grid $grid, Location $location, int $bound): bool
    {
        $column = $grid->getColumn($location->getColumn());

        return $this->sectionIsDiggable($column, $bound);
    }

    /**
     * @param Grid $grid
     * @param Location $location
     * @param int $bound
     *
     * @return bool
     */
    private function blockIsDiggable(Grid $grid, Location $location, int $bound): bool
    {
        $block = $grid->getBlockByLocation($location);

        return $this->sectionIsDiggable($block, $bound);
    }

    /**
     * @param array $cells
     * @param int $bound
     *
     * @return bool
     */
    private function sectionIsDiggable(array $cells, int $bound): bool
    {
        return $this->numberOfFilledInCells($cells) > ($bound - 1);
    }

    /**
     * @param array $cells
     *
     * @return int
     */
    private function numberOfFilledInCells(array $cells): int
    {
        return count(array_diff($cells, [Cell::EMPTY_VALUE]));
    }
}
