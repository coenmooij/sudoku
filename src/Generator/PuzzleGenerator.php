<?php

declare(strict_types=1);

namespace CoenMooij\Sudoku\Generator;

use CoenMooij\Sudoku\Puzzle\Difficulty;
use CoenMooij\Sudoku\Puzzle\Grid;
use CoenMooij\Sudoku\Puzzle\Location;
use CoenMooij\Sudoku\Puzzle\Puzzle;
use CoenMooij\Sudoku\Validator\DigValidator;

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

    public function __construct(DigValidator $digValidator)
    {
        $this->digValidator = $digValidator;
    }

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
     * @return Location[]
     */
    private function getRandomLocations(Difficulty $difficulty): array
    {
        $locationList = [];
        $numberOfHoles = $difficulty->getNumberOfHoles();
        for ($i = 0; $i < $numberOfHoles; $i++) {
            do {
                $location = new Location(random_int(0, 8), random_int(0, 8));
            } while ($this->locationInList($location, $locationList));

            $locationList[] = $location;
        }

        return $locationList;
    }

    private function digLocations(Difficulty $difficulty, Location ...$locationList): void
    {
        $bound = $difficulty->getBound();

        foreach ($locationList as $location) {
            if ($this->digValidator->isDiggableAndUniquelySolvableAfterDigging($this->grid, $location, $bound)) {
                $this->grid->set($location, Grid::EMPTY_VALUE);
            }
        }
    }

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
