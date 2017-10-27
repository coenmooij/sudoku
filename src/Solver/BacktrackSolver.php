<?php

namespace CoenMooij\Sudoku\Solver;

use CoenMooij\Sudoku\Exception\UnsolvableException;
use CoenMooij\Sudoku\Puzzle\Grid;
use CoenMooij\Sudoku\Puzzle\Location;
use CoenMooij\Sudoku\Validator\SudokuValidator;

/**
 * BacktrackSolver - Informed depth first search.
 * The solver will traverse the grid ltr-ttb.
 * Ignores any 'given' cells.
 * For each empty cell it encounters:
 * 1. Gets the possibilities.
 * 2. Randomly fills one of those in.
 * 3. Continues until it reaches the end (solved) or
 *    encounters a cell that has no possibilities
 * 4. Go back to the previous cell with multiple possibilities,
 *    and choose another one from the random stack of options
 * Class BacktrackSolver
 */
class BacktrackSolver implements SudokuSolverInterface
{
    private const DIRECTION_FORWARD = true;
    private const DIRECTION_BACKWARDS = false;

    /**
     * A list of the original given cells of the sudoku puzzle.
     * @var array
     */
    private $givenCells;

    /**
     * A matrix with for each cell a list of possibilities or 'false' if none.
     * @var array
     */
    private $possibilities;

    /**
     * @var Grid
     */
    private $grid;

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

    /**
     * BacktrackSolver constructor.
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * @param Grid $grid
     *
     * @return Grid
     * @throws UnsolvableException
     */
    public function solve(Grid $grid): Grid
    {
        $this->initializeSolveGrid($grid);
        while (!$this->reachedFinalCell()) {
            if (!Grid::locationIsValid($this->getCurrentLocation())) {
                throw new UnsolvableException();
            }
            if (!$this->cellWasGiven()) {
                if ($this->getCurrentDirection() === self::DIRECTION_BACKWARDS) {
                    if ($this->currentLocationHasPossibilities()) {
                        $this->fillCell();
                    } else {
                        $this->emptyCell();
                    }
                } else {
                    $this->findAndSetAllPossibilitiesForCurrentCell();
                    if ($this->currentLocationHasPossibilities()) {
                        $this->fillCell();
                    } else {
                        $this->emptyCell();
                        $this->direction = self::DIRECTION_BACKWARDS;
                    }
                }
            }
            $this->nextCell();
        }

        return $this->reachedFinalCell() ? $grid : false;
    }

    private function getCurrentDirection(): bool
    {
        return $this->direction;
    }

    /**
     * Empties the current cell.
     * @return void
     */
    private function emptyCell()
    {
        $this->grid->emptyCell(new Location($this->row, $this->column));
    }

    private function reachedFinalCell(): bool
    {
        return $this->row === 9 && $this->column === 0;
    }

    /**
     * Resets the class variables.
     * @return void
     */
    private function reset()
    {
        $this->possibilities = [];
        $this->givenCells = [];
        $this->direction = self::DIRECTION_FORWARD;
        $this->column = 0;
        $this->row = 0;
    }

    /**
     * @return bool
     */
    private function currentLocationHasPossibilities(): bool
    {
        return !empty($this->getPossibilitiesFor($this->getCurrentLocation()));
    }

    /**
     * Gets all possible fields from the grid for the current cell.
     * @return void
     */
    private function findAndSetAllPossibilitiesForCurrentCell(): void
    {
        $possibilities = $this->getAllPossibilitiesForCell(new Location($this->row, $this->column));
        $this->possibilities[$this->row][$this->column] = $possibilities;
    }

    /**
     * Moves the row and column pointer 1 forward or backwards based on the current direction.
     */
    private function nextCell(): void
    {
        if ($this->getCurrentDirection() === self::DIRECTION_FORWARD) {
            $this->row = $this->column === 8 ? $this->row + 1 : $this->row;
            $this->column = $this->column === 8 ? 0 : $this->column + 1;
        } else {
            $this->row = $this->column === 0 ? $this->row - 1 : $this->row;
            $this->column = $this->column === 0 ? 8 : $this->column - 1;
        }
    }

    /**
     * Fills a cell with a random choice of one of its possibilities.
     * Then sets direction to forward.
     * @return void
     */
    private function fillCell(): void
    {
        $possibilities = $this->possibilities[$this->row][$this->column];
        $value = $this->getRandomValue($possibilities);
        $this->grid->setCell($this->getCurrentLocation(), $value);
        $this->setPossibilitiesFor($this->getCurrentLocation(), array_values(array_diff($possibilities, [$value])));
        $this->direction = self::DIRECTION_FORWARD;
    }

    private function getCurrentLocation(): Location
    {
        return new Location($this->row, $this->column);
    }

    /**
     * @param Location $location
     *
     * @return int[]
     */
    private function getAllPossibilitiesForCell(Location $location): array
    {
        $impossibilities = array_unique(
            array_merge(
                $this->grid->getRow($location->getRow()),
                $this->grid->getColumn($location->getColumn()),
                $this->grid->getBlock($location)
            )
        );

        return array_values(array_diff(SudokuValidator::ALL_VALID_VALUES, $impossibilities));
    }

    private function getPossibilitiesFor(Location $location): array
    {
        return $this->possibilities[$location->getRow()][$location->getColumn()];
    }

    private function setPossibilitiesFor(Location $location, array $possibilities): void
    {
        $this->possibilities[$location->getRow()][$location->getColumn()] = $possibilities;
    }

    private function initializeSolveGrid(Grid $sudokuGrid): void
    {
        $this->reset();
        $this->grid = $sudokuGrid;
        for ($row = 0; $row < 9; $row++) {
            for ($column = 0; $column < 9; $column++) {
                $location = new Location($row, $column);
                if ($this->isFilledIn($location)) {
                    $this->addGivenCell($location);
                }
            }
        }
    }

    /**
     * @param Location $location
     *
     * @return bool
     */
    private function isFilledIn(Location $location): bool
    {
        return $this->grid->getCellValue($location) !== Grid::EMPTY_CELL;
    }

    /**
     * @param int[] $values
     *
     * @return int
     */
    private function getRandomValue(array $values): int
    {
        return $values[random_int(1, count($values) - 1)];
    }

    /**
     * @param Location $location
     */
    private function addGivenCell(Location $location): void
    {
        $this->givenCells[$location->getRow()][$location->getColumn()] = true;
    }

    /**
     * @return bool
     */
    private function cellWasGiven(): bool
    {
        return !empty($this->givenCells[$this->row][$this->column]);
    }
}
