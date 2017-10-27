<?php

declare(strict_types=1);

namespace CoenMooij\Sudoku\Puzzle;

use CoenMooij\Sudoku\Exception\NoPossibilitiesException;

/**
 * Class Grid
 */
final class Grid
{
    public const NUMBER_OF_ROWS = 9;
    public const NUMBER_OF_COLUMNS = 9;
    public const NUMBER_OF_BLOCKS = 9;
    public const NUMBER_OF_CELLS = 81;

    public const EMPTY_VALUE = 0;

    /**
     * @var int[][]
     */
    private $grid;

    /**
     * @var Cell[]
     */
    private $cells;

    /**
     * @param Cell[] $cells
     * Grid constructor.
     */
    public function __construct(Cell ...$cells)
    {
        $this->cells = $cells;
    }

    /**
     * @param int $index
     *
     * @return Location
     */
    public static function getLocationByIndex(int $index): Location
    {
        $row = (int) floor($index / self::NUMBER_OF_COLUMNS);
        $column = $index % self::NUMBER_OF_ROWS;

        return new Location($row, $column);
    }

    /**
     * @param $location
     *
     * @return bool
     */
    public static function locationIsValid(Location $location): bool
    {
        return $location->getRow() >= 0 && $location->getRow() < 9
            && $location->getColumn() >= 0 && $location->getColumn() < 9;
    }

    /**
     * @return int
     */
    public function numberOfEmptyFields(): int
    {
        return self::NUMBER_OF_CELLS - count($this->cells);
    }

    public function getCellValue(Location $location): int
    {
        return $this->grid[$location->getRow()][$location->getColumn()];
    }

    public function getCell(Location $location): Cell
    {
        return $this->grid[$location->getRow()][$location->getColumn()];
    }

    public function setCell(Location $location, int $value): void
    {
        if ($this->isValid($value)) {
            $this->grid[$location->getRow()][$location->getColumn()] = $value;
        }
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
     * @param int $column
     *
     * @return int[]
     */
    public function getColumn(int $column): array
    {
        $values = [];
        for ($row = 0; $row < 9; $row++) {
            $values[] = $this->getCellValue(new Location($row, $column));
        }

        return $values;
    }

    /**
     * @return int[][]
     */
    public function getAllBlocks(): array
    {
        $blocks = [];
        for ($i = 0; $i < self::NUMBER_OF_BLOCKS; $i++) {
            $blocks[] = $this->getBlockByNumber($i);
        }

        return $blocks;
    }

    /**
     * @param integer $block
     *
     * @return int[]
     */
    public function getBlockByNumber(int $block): array
    {
        $row = $block - ($block % 3);
        $column = ($block % 3) * 3;

        return $this->getBlockByLocation(new Location($row, $column));
    }

    /**
     * @param Location $location
     *
     * @return array
     */
    public function getBlockByLocation(Location $location): array
    {
        $firstCellInBlock = $this->getFirstCellInBlock($location);
        $block = [];
        for ($row = 0; $row < 3; $row++) {
            for ($column = 0; $column < 3; $column++) {
                $block[] = $this->getCellValue(
                    new Location(
                        $firstCellInBlock->getRow() + $row,
                        $firstCellInBlock->getColumn() + $column
                    )
                );
            }
        }

        return $block;
    }

    /**
     * @param Location $location
     *
     * @return Location
     */
    private function getFirstCellInBlock(Location $location): Location
    {
        $firstRowInBlock = $location->getRow() - $location->getRow() % 3;
        $firstColumnInBlock = $location->getColumn() - $location->getColumn() % 3;

        return new Location($firstRowInBlock, $firstColumnInBlock);
    }

    /**
     * @param Location $location
     *
     * @return array
     * @throws NoPossibilitiesException
     */
    public function possibilitiesForCell(Location $location): array
    {
        if ($this->getCellValue($location) > 0) {
            throw new NoPossibilitiesException();
        }
        $impossibilities = array_unique(
            array_merge(
                $this->getRow($location->getRow()),
                $this->getColumn($location->getColumn()),
                $this->getBlockByLocation($location)
            )
        );

        return array_filter(array_values(array_diff(Cell::POSSIBLE_VALUES, $impossibilities)));
    }

    /**
     * @param Location $location
     */
    public function emptyCell(Location $location): void
    {
        $this->setCell($location, Cell::EMPTY_VALUE);
    }

    /**
     * @param $value
     *
     * @return bool
     */
    private function isValid($value): bool
    {
        return $value >= 0 && $value <= 9;
    }

    /**
     * @param $location
     *
     * @return bool
     */
    public function isEmpty($location): bool
    {
        return $this->getCell($location)->isEmpty();
    }

    /**
     * @return Cell[]
     */
    public function getAllFilledCells(): array
    {
        return $this->cells;
    }
}
