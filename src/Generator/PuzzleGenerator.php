<?php

declare(strict_types=1);

namespace CoenMooij\Sudoku\Generator;

use CoenMooij\Sudoku\DigValidator;
use CoenMooij\Sudoku\Puzzle\Grid;
use CoenMooij\Sudoku\Puzzle\Location;
use CoenMooij\Sudoku\Puzzle\Puzzle;

/**
 * Class PuzzleGenerator
 */
final class PuzzleGenerator
{
    const DIFFICULTY_LEVELS = [
        ['level' => 1, 'holes' => 30, 'bound' => 5],
        ['level' => 2, 'holes' => 40, 'bound' => 4],
        ['level' => 3, 'holes' => 50, 'bound' => 3],
        ['level' => 4, 'holes' => 60, 'bound' => 2],
        ['level' => 5, 'holes' => 70, 'bound' => 0],
    ];

    /**
     * @var integer
     */
    private $difficulty;

    /**
     * A list of cell locations to be dug out.
     * @var array
     */
    private $stack;

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
     */
    public function __construct(DigValidator $digValidator)
    {
        $this->digValidator = $digValidator;
    }

    /**
     * Generate a sudoku puzzle from a given solution.
     *
     * @param Grid $grid A full sudoku solution.
     * @param integer $difficulty The difficulty level.
     *
     * @return Puzzle
     */
    public function generatePuzzle(Grid $grid, $difficulty): Puzzle
    {
        $this->grid = $grid;
        $this->difficulty = $difficulty;
        $this->populateRandomStack();
        $this->digHoles();

        return new Puzzle($this->grid);
    }

    /**
     * Populates the stack with a list of random cell values.
     * @return void
     */
    private function populateRandomStack(): void
    {
        $numberOfHoles = self::DIFFICULTY_LEVELS[$this->difficulty - 1]['holes'];
        for ($i = 0; $i < $numberOfHoles; $i++) {
            $this->stack[] = ['x' => random_int(0, 8), 'y' => random_int(0, 8)];
        }
    }

    /**
     * Empty all the cells from stack in the grid if possible.
     * @return void
     */
    private function digHoles(): void
    {
        $numberOfHoles = self::DIFFICULTY_LEVELS[$this->difficulty - 1]['holes'];
        $bound = self::DIFFICULTY_LEVELS[$this->difficulty - 1]['bound'];
        for ($i = 0; $i < $numberOfHoles; $i++) {
            $location = new Location($this->stack[$i]['y'], $this->stack[$i]['x']);
            if ($this->digValidator->isDiggableAndUniquelySolvableAfterDigging($this->grid, $location, $bound)) {
                $this->grid->emptyCell($location);
            }
        }
    }
}
