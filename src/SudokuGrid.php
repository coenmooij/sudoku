<?php

namespace CoenMooij\Sudoku;

/**
 * Class SudokuGrid
 */
class SudokuGrid
{
    const EMPTY_CELL = 0;

    /** @var int[] */
    private $grid;

    /**
     * SudokuGrid constructor.
     */
    public function __construct()
    {
        $this->initializeGrid();
    }

    /**
     * Initializes the grid to empty fields.
     * @return void
     */
    private function initializeGrid()
    {
        $grid = [];
        for ($i = 0; $i < 9; $i++) {
            for ($j = 0; $j < 9; $j++) {
                $grid[$i][$j] = 0;
            }
        }
        $this->grid = $grid;
    }

    public function setCell(GridLocation $location, int $value): void
    {
        if ($this->isValid($value)) {
            $this->grid[$location->getRow()][$location->getColumn()] = $value;
        }
    }

    public function getCell(GridLocation $location): int
    {
        return $this->grid[$location->getRow()][$location->getColumn()];
    }

    /**
     * Setter for the internal grid.
     *
     * @param array $grid The new grid.
     */
    public function setGrid($grid)
    {
        $this->grid = $grid;
    }

    /**
     * Getter for the grid.
     * @return array
     */
    public function getGrid()
    {
        return $this->grid;
    }

    public function getGridAsString()
    {
        $gridAsString = '';
        foreach ($this->grid as $row) {
            foreach ($row as $value) {
                $gridAsString .= (string) $value;
            }
        }

        return $gridAsString;
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
            $response[] = $this->getCell($row, $column);
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

    public function getBlock(GridLocation $location): array
    {
        $firstCellInBlock = $this->getFirstCellInBlock($location);
        $block = [];
        for ($row = 0; $row < 3; $row++) {
            for ($column = 0; $column < 3; $column++) {
                $block[] = $this->getCell(
                    new GridLocation(
                        $firstCellInBlock->getRow() + $row,
                        $firstCellInBlock->getColumn() + $column
                    )
                );
            }
        }

        return $block;
    }

    private function getFirstCellInBlock(GridLocation $location): GridLocation
    {
        $firstRowInBlock = $location->getRow() - $location->getRow() % 3;
        $firstColumnInBlock = $location->getColumn() - $location->getColumn() % 3;

        return new GridLocation($firstRowInBlock, $firstColumnInBlock);
    }

    public function possibilitiesForCell(GridLocation $location): array
    {
        if ($this->getCell($location) > 0) {
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

    public function emptyCell(GridLocation $location): void
    {
        $this->setCell($location, self::EMPTY_CELL);
    }

    private function isValid($value): bool
    {
        return $value >= 0 && $value <= 9;
    }

    public function isEmpty($location): bool
    {
        return $this->getCell($location) !== self::EMPTY_CELL;
    }
}
