<?php

declare(strict_types=1);

namespace CoenMooij\Sudoku\Validator;

use CoenMooij\Sudoku\Exception\UnsolvableException;
use CoenMooij\Sudoku\Puzzle\Grid;
use CoenMooij\Sudoku\Puzzle\Location;
use CoenMooij\Sudoku\Solver\BacktrackSolver;

final class DigValidator
{
    /**
     * @var BacktrackSolver
     */
    private $solver;

    public function __construct(BacktrackSolver $solver)
    {
        $this->solver = $solver;
    }

    public function isDiggableAndUniquelySolvableAfterDigging(Grid $sudokuGrid, Location $location, int $bound): bool
    {
        return $this->isDiggable($sudokuGrid, $location, $bound)
            && $this->isUniquelySolvableAfterDigging($sudokuGrid, $location);
    }

    private function isDiggable(Grid $grid, Location $location, int $bound): bool
    {
        return $bound <= 0 || (
                $this->rowIsDiggable($grid, $location, $bound)
                && $this->columnIsDiggable($grid, $location, $bound)
                && $this->blockIsDiggable($grid, $location, $bound)
            );
    }

    private function isUniquelySolvableAfterDigging(Grid $grid, Location $location): bool
    {
        $gridCopy = clone $grid;
        $gridCopy->empty($location);
        $possibleValues = $gridCopy->getAllPossibilitiesFor($location);
        if (count($possibleValues) > 1) {
            $possibleValues = array_diff($possibleValues, [$grid->get($location)]);
            foreach ($possibleValues as $possibleValue) {
                $gridCopy->set($location, $possibleValue);
                try {
                    $this->solver->solve($gridCopy);
                } catch (UnsolvableException $exception) {
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
        $block = $grid->getBlockAt($location);

        return $this->sectionIsDiggable($block, $bound);
    }

    private function sectionIsDiggable(array $values, int $bound): bool
    {
        return $this->numberOfNonEmptyValues($values) > $bound;
    }

    /**
     * @param int[] $values
     *
     * @return int
     */
    private function numberOfNonEmptyValues(array $values): int
    {
        return count(array_diff($values, [Grid::EMPTY_VALUE]));
    }
}
