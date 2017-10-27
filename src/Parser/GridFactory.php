<?php

namespace CoenMooij\Sudoku\Puzzle;

use CoenMooij\Sudoku\SudokuParser;

class GridFactory
{
    /**
     * @var SudokuParser
     */
    private $parser;

    public function __construct(SudokuParser $parser)
    {
        $this->parser = $parser;
    }
    public function createFromString(string $grid): Grid
    {
     return $this->parser->parse($grid);
    }
}
