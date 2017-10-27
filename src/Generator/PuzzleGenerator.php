<?php

declare(strict_types=1);

namespace CoenMooij\Sudoku\Generator;

use CoenMooij\Sudoku\Puzzle\Cell;
use CoenMooij\Sudoku\Puzzle\Difficulty;
use CoenMooij\Sudoku\Puzzle\Grid;
use CoenMooij\Sudoku\Puzzle\Location;
use CoenMooij\Sudoku\Puzzle\Puzzle;
use CoenMooij\Sudoku\Validator\DigValidator;

/**
 * Class PuzzleGenerator
 */
final class PuzzleGenerator
{
    /**
     * @var Grid
     */
    private $grid;

    /**
     * @var DigValidator
     */
    private $digValidator;

    /**
     * PuzzleGenerator constructor.
     *
     * @param DigValidator $digValidator
     */
    public function __construct(DigValidator $digValidator)
    {
        $this->digValidator = $digValidator;
    }

    /**
     * @param Grid $solvedGrid
     * @param Difficulty $difficulty
     *
     * @return Puzzle
     */
    public function generatePuzzle(Grid $solvedGrid, Difficulty $difficulty): Puzzle
    {
        $this->grid = $solvedGrid;
        $locationList = $this->getRandomLocations($difficulty);
        $this->digLocations($difficulty, ...$locationList);

        return new Puzzle($this->grid);
    }

    /**
     * @param Difficulty $difficulty
     *
     * @return array|Location[]
     */
    private function getRandomLocations(Difficulty $difficulty): array
    {
        $locationList = [];
        $numberOfHoles = $difficulty->getNumberOfHoles();
        for ($i = 0; $i < $numberOfHoles; $i++) {
            do {
                $location = new Location(random_int(0, 8), random_int(0, 8));
            } while ($this->locationInList($location, $locationList));

            $locationList[] = new Location(random_int(0, 8), random_int(0, 8));// todo fix bug of duplicates
        }

        return $locationList;
    }

    /**
     * @param Difficulty $difficulty
     * @param Location[] ...$locationList
     */
    private function digLocations(Difficulty $difficulty, Location ...$locationList): void
    {
        $bound = $difficulty->getBound();

        foreach ($locationList as $location) {
            if ($this->digValidator->isDiggableAndUniquelySolvableAfterDigging($this->grid, $location, $bound)) {
                $this->grid->setCell($location, Cell::EMPTY_VALUE);
            }
        }
    }

    /**
     * @param Location $needle
     * @param Location[] $locationList
     *
     * @return bool
     */
    private function locationInList(Location $needle, Location ...$locationList): bool
    {
        foreach ($locationList as $location) {
            if (Location::match($needle, $location)) {
                return true;
            }
        }

        return false;
    }
}
