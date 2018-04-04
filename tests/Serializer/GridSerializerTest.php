<?php

namespace CoenMooij\Sudoku\Serializer;

use CoenMooij\Sudoku\Puzzle\Grid;
use CoenMooij\Sudoku\Puzzle\Location;
use PHPUnit\Framework\TestCase;

class GridSerializerTest extends TestCase
{
    private const SERIALIZED_GRID = '004060002000000000000000000000000000000000000000050000000000000000000000000000040';
    private const LOCATION_VALUE_PAIRS = [
        [0, 2, 4],
        [0, 4, 6],
        [0, 8, 2],
        [5, 4, 5],
        [8, 7, 4],
    ];
    const EMPTY_LOCATIONS = [
        [4, 4],
        [5, 7],
    ];

    /**
     * @test
     */
    public function serialize(): void
    {
        $grid = new Grid();
        foreach ($this->dataProvider() as $data) {
            if ($data[1] !== Grid::EMPTY_VALUE) {
                $grid->set($data[0], $data[1]);
            }
        }
        $serializedGrid = GridSerializer::serialize($grid);

        self::assertEquals(self::SERIALIZED_GRID, $serializedGrid);
    }

    /**
     * @test
     * @dataProvider dataProvider
     */
    public function deserialize(Location $location, int $value): void
    {
        $grid = GridSerializer::deserialize(self::SERIALIZED_GRID);

        self::assertEquals($grid->get($location), $value);
    }

    public function dataProvider(): array
    {
        $data = [];
        foreach (self::LOCATION_VALUE_PAIRS as $array) {
            $data[] = [new Location($array[0], $array[1]), $array[2]];
        }

        foreach (self::EMPTY_LOCATIONS as $array) {
            $data[] = [new Location($array[0], $array[1]), Grid::EMPTY_VALUE];
        }

        return $data;
    }
}
