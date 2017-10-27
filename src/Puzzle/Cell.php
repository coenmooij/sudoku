<?php

namespace CoenMooij\Sudoku\Puzzle;

class Cell
{
    /**
     * @var Location
     */
    private $location;
    /**
     * @var int
     */
    private $value;

    public function __construct(Location $location, int $value)
    {
        $this->location = $location;
        $this->value = $value;
    }

    /**
     * @return Location
     */
    public function getLocation(): Location
    {
        return $this->location;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param int $value
     */
    public function setValue(int $value): void
    {
        $this->value = $value;
    }
}
