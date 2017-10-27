<?php

namespace CoenMooij\Sudoku;

class Cell
{
    /**
     * @var GridLocation
     */
    private $location;
    /**
     * @var int
     */
    private $value;

    public function __construct(GridLocation $location, int $value)
    {
        $this->location = $location;
        $this->value = $value;
    }

    /**
     * @return GridLocation
     */
    public function getLocation(): GridLocation
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
