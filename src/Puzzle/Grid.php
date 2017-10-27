<?php

namespace CoenMooij\Sudoku\Puzzle;

use CoenMooij\Sudoku\Parser\GridSerializer;

/**
 * Class Grid
 */
class Grid
{
    public const NUMBER_OF_ROWS = 9;
    public const NUMBER_OF_COLUMNS = 9;
    public const NUMBER_OF_BLOCKS = 9;
    public const NUMBER_OF_CELLS = 81;
    public const EMPTY_CELL = 0;

    /**
     * @var int[][]
     */
    private $grid;

    /**
     * Todo : find better solution to calculate it without serializer
     *
     * @param Grid $grid
     *
     * @return int
     */
    public static function numberOfEmptyFields(Grid $grid): int
    {
        return substr_count(GridSerializer::serialize($grid), (string) Grid::EMPTY_CELL);
    }

    /**
     * @param int $index
     *
     * @return Location
     */
    public static function getLocationByIndex(int $index): Location
    {
        $row = floor($index / Grid::NUMBER_OF_COLUMNS);
        $column = $index % Grid::NUMBER_OF_ROWS;

        return new Location($row, $column);
    }

    /**
     * @param Cell[] $cells
     * Grid constructor.
     */
    public function __construct(array $cells = [])
    {
        $this->initializeEmptyGrid();
        foreach ($cells as $cell) {
            $this->setCell($cell->getLocation(), $cell->getValue());
        }
    }

    /**
     * @return void
     */
    private function initializeEmptyGrid(): void
    {
        $grid = [];
        for ($i = 0; $i < 9; $i++) {
            for ($j = 0; $j < 9; $j++) {
                $grid[$i][$j] = self::EMPTY_CELL;
            }
        }
        $this->grid = $grid;
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

    public function getCellValue(Location $location): int
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
     * Setter for the internal grid.
     *
     * @param array $grid The new grid.
     */
    public function setGrid($grid): void
    {
        $this->grid = $grid;
    }

    /**
     * Getter for the grid.
     * @return array
     */
    public function getGrid(): Grid
    {
        return $this->grid;
    }

    /**
     * Returns given row of the grid.
     *
     * @param integer $rowNumber The row number.
     *
     * @return array
     */
    public function getRow(int $rowNumber)
    {
        return $this->grid[$rowNumber];
    }

    /**
     * Returns a given column.
     *
     * @param integer $column The column number.
     *
     * @return array
     */
    public function getColumn(int $column)
    {
        $response = [];
        for ($row = 0; $row < 9; $row++) {
            $response[] = $this->getCellValue($row, $column);
        }

        return $response;
    }

    /**
     * Returns block by number (ltr,ttb) (0-8)
     *
     * @param integer $blockNumber The block number.
     *
     * @return array
     */
    public function getBlockByNumber(int $blockNumber)
    {
        $mod3 = $blockNumber % 3;
        $column = $mod3 * 3;
        $row = $blockNumber - $mod3;

        return $this->getBlock($row, $column);
    }

    public function getBlock(Location $location): array
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

    private function getFirstCellInBlock(Location $location): Location
    {
        $firstRowInBlock = $location->getRow() - $location->getRow() % 3;
        $firstColumnInBlock = $location->getColumn() - $location->getColumn() % 3;

        return new Location($firstRowInBlock, $firstColumnInBlock);
    }

    public function possibilitiesForCell(Location $location): array
    {
        if ($this->getCellValue($location) > 0) {
            throw new NoPossibilitiesException();
        }
        $impossibilities = array_unique(
            array_merge(
                $this->getRow($location->getRow()),
                $this->getColumn($location->getColumn()),
                $this->getBlock($location)
            )
        );
        $array = [1, 2, 3, 4, 5, 6, 7, 8, 9];

        // todo throw exception if no possibilities

        return array_filter(array_values(array_diff($array, $impossibilities)));
    }

    public function emptyCell(Location $location): void
    {
        $this->setCell($location, self::EMPTY_CELL);
    }

    private function isValid($value): bool
    {
        return $value >= 0 && $value <= 9;
    }

    public function isEmpty($location): bool
    {
        return $this->getCellValue($location) !== self::EMPTY_CELL;
    }
}
