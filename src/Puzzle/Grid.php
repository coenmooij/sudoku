<?php

declare(strict_types=1);

namespace CoenMooij\Sudoku\Puzzle;

use CoenMooij\Sudoku\Exception\InvalidValueException;

final class Grid
{
    public const NUMBER_OF_ROWS = 9;
    public const NUMBER_OF_COLUMNS = 9;
    public const NUMBER_OF_BLOCKS = 9;
    public const NUMBER_OF_LOCATIONS = 81;
    public const POSSIBLE_VALUES = [1, 2, 3, 4, 5, 6, 7, 8, 9];
    public const EMPTY_VALUE = 0;

    /**
     * @var int[][]
     */
    private $grid;

    public function __construct()
    {
        $this->initializeGrid();
    }

    public static function valueIsValid(int $value): bool
    {
        return in_array($value, self::POSSIBLE_VALUES, true);
    }

    public function get(Location $location): int
    {
        return $this->grid[$location->getRow()][$location->getColumn()];
    }

    public function set(Location $location, int $value): void
    {
        if (!self::valueIsValid($value)) {
            throw new InvalidValueException();
        }
        $this->grid[$location->getRow()][$location->getColumn()] = $value;
    }

    /**
     * @return int[][]
     */
    public function getRows(): array
    {
        $rows = [];
        for ($i = 0; $i < self::NUMBER_OF_ROWS; $i++) {
            $rows[] = $this->getRow($i);
        }

        return $rows;
    }

    /**
     * @param int $row
     *
     * @return int[]
     */
    public function getRow(int $row): array
    {
        return $this->grid[$row];
    }

    /**
     * @return int[][]
     */
    public function getColumns(): array
    {
        $columns = [];
        for ($i = 0; $i < self::NUMBER_OF_COLUMNS; $i++) {
            $columns[] = $this->getColumn($i);
        }

        return $columns;
    }

    /**
     * @param int $column
     *
     * @return int[]
     */
    public function getColumn(int $column): array
    {
        $values = [];
        for ($row = 0; $row < 9; $row++) {
            $values[] = $this->grid[$row][$column];
        }

        return $values;
    }

    /**
     * @return int[][]
     */
    public function getBlocks(): array
    {
        $blocks = [];
        for ($i = 0; $i < self::NUMBER_OF_BLOCKS; $i++) {
            $blocks[] = $this->getBlock($i);
        }

        return $blocks;
    }

    /**
     * @param int $block
     *
     * @return int[]
     */
    public function getBlock(int $block): array
    {
        $row = $block - ($block % 3);
        $column = ($block % 3) * 3;

        return $this->getBlockAt(new Location($row, $column));
    }

    /**
     * @param Location $location
     *
     * @return int[]
     */
    public function getBlockAt(Location $location): array
    {
        $firstLocation = $this->getFirstLocationInBlock($location);
        $block = [];
        for ($row = 0; $row < 3; $row++) {
            for ($column = 0; $column < 3; $column++) {
                $block[] = $this->grid[$firstLocation->getRow() + $row][$firstLocation->getColumn() + $column];
            }
        }

        return $block;
    }

    private function getFirstLocationInBlock(Location $location): Location
    {
        $firstRowInBlock = $location->getRow() - $location->getRow() % 3;
        $firstColumnInBlock = $location->getColumn() - $location->getColumn() % 3;

        return new Location($firstRowInBlock, $firstColumnInBlock);
    }

    /**
     * @param Location $location
     *
     * @return int[]
     */
    public function getAllPossibilitiesFor(Location $location): array
    {
        $impossibleValues = array_unique(
            array_merge(
                $this->getRow($location->getRow()),
                $this->getColumn($location->getColumn()),
                $this->getBlockAt($location)
            )
        );

        return array_values(array_diff(self::POSSIBLE_VALUES, $impossibleValues));
    }

    public function isEmpty($location): bool
    {
        return $this->get($location) === self::EMPTY_VALUE;
    }

    public function empty(Location $location): void
    {
        $this->grid[$location->getRow()][$location->getColumn()] = self::EMPTY_VALUE;
    }

    public function numberOfEmptyFields(): int
    {
        $numberOfEmptyFields = 0;
        foreach ($this->getRows() as $row) {
            for ($i = 0; $i < self::NUMBER_OF_COLUMNS; $i++) {
                $numberOfEmptyFields += ($row[$i] === self::EMPTY_VALUE);
            }
        }

        return $numberOfEmptyFields;
    }

    private function initializeGrid(): void
    {
        for ($row = 0; $row < self::NUMBER_OF_ROWS; $row++) {
            for ($column = 0; $column < self::NUMBER_OF_COLUMNS; $column++) {
                $this->empty(new Location($row, $column));
            }
        }
    }
}
