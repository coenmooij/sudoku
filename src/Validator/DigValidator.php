<?php

namespace CoenMooij\Sudoku;

class DigValidator
{
    /**
     * @var SudokuSolverInterface
     */
    private $solver;

    // Todo proper dependency injection
    public function __construct()
    {
        $this->solver = new BacktrackSolver();
    }

    public function isDiggableAndUniquelySolvableAfterDigging(
        Grid $sudokuGrid,
        Location $location,
        int $bound
    ): bool {
        return $this->cellIsDiggable($sudokuGrid, $location, $bound)
            && $this->cellIsUniquelySolvableAfterDigging($sudokuGrid, $location);
    }

    private function cellIsDiggable(Grid $grid, Location $location, int $bound): bool
    {
        return $bound <= 0 || (
                $this->rowIsDiggable($grid, $location, $bound)
                && $this->columnIsDiggable($grid, $location, $bound)
                && $this->blockIsDiggable($grid, $location, $bound)
            );
    }

    private function cellIsUniquelySolvableAfterDigging(Grid $grid, Location $location): bool
    {
        $originalValue = $grid->getCell($location);
        $gridCopy = clone $grid;
        $gridCopy->emptyCell($location);
        $possibilities = $gridCopy->possibilitiesForCell($location);
        if (count($possibilities) > 1) {
            $possibilities = array_diff($possibilities, [$originalValue]);
            foreach ($possibilities as $possibility) {
                $gridCopy->setCell($location, $possibility);
                if ($this->solver->solve($gridCopy)) {
                    return false;
                }
            }
        }

        return true;
    }

    private function rowIsDiggable(Grid $grid, Location $location, int $bound): bool
    {
        $row = $grid->getRow($location->getRow());

        return $this->sectionIsDiggable($row, $bound);
    }

    private function columnIsDiggable(Grid $grid, Location $location, int $bound): bool
    {
        $column = $grid->getColumn($location->getColumn());

        return $this->sectionIsDiggable($column, $bound);
    }

    private function blockIsDiggable(Grid $grid, Location $location, int $bound): bool
    {
        $block = $grid->getBlock($location);

        return $this->sectionIsDiggable($block, $bound);
    }

    private function sectionIsDiggable(array $cells, int $bound): bool
    {
        return $this->numberOfFilledInCells($cells) > ($bound - 1);
    }

    private function numberOfFilledInCells(array $cells): int
    {
        return count(array_diff($cells, [Grid::EMPTY_CELL]));
    }
}
