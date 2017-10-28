<?php

declare(strict_types=1);

namespace CoenMooij\Sudoku\Solver;

use CoenMooij\Sudoku\Exception\UnsolvableException;
use CoenMooij\Sudoku\Puzzle\Grid;
use CoenMooij\Sudoku\Puzzle\Location;

/**
 * Informed depth first search.
 */
class BacktrackSolver implements SudokuSolverInterface
{
    private const DIRECTION_FORWARDS = true;
    private const DIRECTION_BACKWARDS = false;

    /**
     * @var Grid
     */
    private $grid;

    /**
     * @var int[][][]
     */
    private $possibleValues;

    /**
     * @var bool[][]
     */
    private $presetValues;

    /**
     * @var bool
     */
    private $direction;

    /**
     * @var int
     */
    private $row;

    /**
     * @var int
     */
    private $column;

    public function solve(Grid $grid): Grid
    {
        $this->initialize($grid);

        while ($this->locationIsValid()) {
            if (!$this->isPresetValue()) {
                $this->direction === self::DIRECTION_FORWARDS
                    ? $this->handleForwardsIteration()
                    : $this->handleBackwardsIteration();
            }
            $this->nextLocation();
        }
        if (!$this->reachedEndOfGrid()) {
            throw new UnsolvableException();
        }

        return $grid;
    }

    private function handleForwardsIteration(): void
    {
        $this->findAllPossibleValuesForCurrentLocation();
        if ($this->currentLocationHasPossibleValues()) {
            $this->fillCurrentLocationWithNextPossibleValue();
        } else {
            $this->grid->empty($this->getCurrentLocation());
            $this->direction = self::DIRECTION_BACKWARDS;
        }
    }

    private function handleBackwardsIteration(): void
    {
        if ($this->currentLocationHasPossibleValues()) {
            $this->fillCurrentLocationWithNextPossibleValue();
            $this->direction = self::DIRECTION_FORWARDS;
        } else {
            $this->grid->empty($this->getCurrentLocation());
        }
    }

    private function initialize(Grid $grid): void
    {
        $this->direction = self::DIRECTION_FORWARDS;
        $this->column = 0;
        $this->row = 0;
        $this->grid = $grid;
        $this->possibleValues = [];
        $this->presetValues = [];
        $this->initializePresetValues();
    }

    private function initializePresetValues(): void
    {
        for ($row = 0; $row < 9; $row++) {
            for ($column = 0; $column < 9; $column++) {
                $location = new Location($row, $column);
                if ($this->grid->isEmpty($location)) {
                    $this->presetValues[$row][$column] = false;
                } else {
                    $this->presetValues[$row][$column] = true;
                }
            }
        }
    }

    private function currentLocationHasPossibleValues(): bool
    {
        return !empty($this->possibleValues[$this->row][$this->column]);
    }

    private function findAllPossibleValuesForCurrentLocation(): void
    {
        $possibilities = $this->grid->getAllPossibilitiesFor(new Location($this->row, $this->column));
        shuffle($possibilities);
        $this->possibleValues[$this->row][$this->column] = $possibilities;
    }

    private function nextLocation(): void
    {
        if ($this->direction === self::DIRECTION_FORWARDS) {
            $this->row = $this->column === 8 ? $this->row + 1 : $this->row;
            $this->column = $this->column === 8 ? 0 : $this->column + 1;
        } else {
            $this->row = $this->column === 0 ? $this->row - 1 : $this->row;
            $this->column = $this->column === 0 ? 8 : $this->column - 1;
        }
    }

    private function fillCurrentLocationWithNextPossibleValue(): void
    {
        $this->grid->set($this->getCurrentLocation(), $this->possibleValues[$this->row][$this->column][0]);
        array_shift($this->possibleValues[$this->row][$this->column]);
    }

    private function getCurrentLocation(): Location
    {
        return new Location($this->row, $this->column);
    }

    private function isPresetValue(): bool
    {
        return !empty($this->presetValues[$this->row][$this->column]);
    }

    private function locationIsValid(): bool
    {
        return $this->row >= 0 && $this->row < 9;
    }

    private function reachedEndOfGrid(): bool
    {
        return $this->row > 8;
    }
}
