<?php

namespace CoenMooij\Sudoku\Solver;

use CoenMooij\Sudoku\SudokuGrid;

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
     * @var SudokuGrid
     */
    private $sudokuGrid;

    /**
     * @var boolean
     */
    private $direction;

    /**
     * @var integer
     */
    private $row;

    /**
     * @var integer
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
     * Solves the given grid.
     *
     * @param SudokuGrid $sudokuGrid The grid to solve.
     *
     * @return SudokuGrid
     * @throws \CoenMooij\Sudoku\UnsolvableException
     */
    public function solve(SudokuGrid $sudokuGrid): SudokuGrid
    {
        $this->initializeSolveGrid($sudokuGrid);
        while (!$this->reachedFinalCell()) {
            if (!$this->locationIsValid()) {
                throw new UnsolvableException();
            }
            if (!$this->cellIsGiven()) {
                if ($this->getCurrentDirection() === self::DIRECTION_BACKWARDS) {
                    if ($this->hasPossibilities()) {
                        $this->fillCell();
                    } else {
                        $this->emptyCell();
                    }
                } else {
                    $this->findAndSetAllPossibilitiesForCurrentCell();
                    if ($this->hasPossibilities()) {
                        $this->fillCell();
                    } else {
                        $this->emptyCell();
                        $this->direction = self::DIRECTION_BACKWARDS;
                    }
                }
            }
            $this->nextCell();
        }

        return $this->reachedFinalCell() ? $sudokuGrid : false;
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
        $this->sudokuGrid->emptyCell(new GridLocation($this->row, $this->column));
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
     * Checks whether the current cell has any possibilities.
     * @return boolean
     */
    private function hasPossibilities()
    {
        return !empty($this->possibilities[$this->row][$this->column]);
    }

    /**
     * Gets all possible fields from the grid for the current cell.
     * @return void
     */
    private function findAndSetAllPossibilitiesForCurrentCell(): void
    {
        $possibilities = $this->sudokuGrid->possibilitiesForCell(new GridLocation($this->row, $this->column));
        $this->possibilities[$this->row][$this->column] = $possibilities;
    }

    private function currentDirectionIsBackwards(): bool
    {
        return $this->direction === self::DIRECTION_BACKWARDS;
    }

    /**
     * Checks whether our current location ($row, $column) is still a valid position.
     * @return boolean
     */
    private function locationIsValid(): bool
    {
        return $this->column >= 0 && $this->column < 9 && $this->row >= 0 && $this->row < 9;
    }

    /**
     * Moves the row and column pointer 1 forward or backwards based on the current direction.
     */
    private function nextCell(): void
    {
        if ($this->direction == self::DIRECTION_FORWARD) {
            $this->row = $this->column == 8 ? $this->row + 1 : $this->row;
            $this->column = $this->column == 8 ? 0 : $this->column + 1;
        } else {
            $this->row = $this->column == 0 ? $this->row - 1 : $this->row;
            $this->column = $this->column == 0 ? 8 : $this->column - 1;
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
        $this->sudokuGrid->setCell($this->getCurrentLocation(), $value);
        $this->setPossibilitiesFor($this->getCurrentLocation(), array_values(array_diff($possibilities, [$value])));
        $this->direction = self::DIRECTION_FORWARD;
    }

    private function getCurrentLocation(): GridLocation
    {
        return new GridLocation($this->row, $this->column);
    }

    private function getPossibilitiesFor(GridLocation $location): array
    {
        return $this->possibilities[$location->getRow()][$location->getColumn()];
    }

    private function setPossibilitiesFor(GridLocation $location, array $possibilities): void
    {
        $this->possibilities[$location->getRow()][$location->getColumn()] = $possibilities;
    }

    private function initializeSolveGrid(SudokuGrid $sudokuGrid): void
    {
        $this->reset();
        $this->sudokuGrid = $sudokuGrid;
        for ($row = 0; $row < 9; $row++) {
            for ($column = 0; $column < 9; $column++) {
                $location = new GridLocation($row, $column);
                if ($this->isFilledIn($location)) {
                    $this->addGivenCell($location);
                }
            }
        }
    }

    private function addGivenCell(GridLocation $location): void
    {
        $this->givenCells[$location->getRow()][$location->getColumn()] = true;
    }

    private function isFilledIn(GridLocation $location): bool
    {
        return $this->sudokuGrid->getCell($location) !== SudokuGrid::EMPTY_CELL;
    }

    /**
     * Selects a random value of an array of values.
     *
     * @param array $values The values.
     *
     * @return mixed
     */
    private function getRandomValue(array $values)
    {
        $random = rand(1, count($values));

        return $values[($random - 1)];
    }

    /**
     * Checks if cell was given in original puzzle.
     * @return boolean
     */
    private function cellIsGiven(): bool
    {
        return !empty($this->givenCells[$this->row][$this->column]);
    }
}
