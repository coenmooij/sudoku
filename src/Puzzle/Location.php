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

    public function __construct(int $row, int $column)
    {
        $this->row = $row;
        $this->column = $column;
    }

    public static function match(Location $location1, Location $location2): bool
    {
        return $location1->getRow() === $location2->getRow() && $location1->getColumn() === $location2->getColumn();
    }

    public function getRow(): int
    {
        return $this->row;
    }

    public function getColumn(): int
    {
        return $this->column;
    }
}
