<?php

declare(strict_types=1);

namespace CoenMooij\Sudoku\Puzzle;

/**
 * Class Location
 */
final class Location
{
    /**
     * @var int
     */
    private $row;

    /**
     * @var int
     */
    private $column;

    /**
     * Location constructor.
     *
     * @param int $row
     * @param int $column
     */
    public function __construct(int $row, int $column)
    {
        $this->row = $row;
        $this->column = $column;
    }

    /**
     * @param Location $location1
     * @param Location $location2
     *
     * @return bool
     */
    public static function match(Location $location1, Location $location2): bool
    {
        return $location1->getRow() === $location2->getRow() && $location1->getColumn() === $location2->getColumn();
    }

    /**
     * @return int
     */
    public function getRow(): int
    {
        return $this->row;
    }

    /**
     * @return int
     */
    public function getColumn(): int
    {
        return $this->column;
    }
}
