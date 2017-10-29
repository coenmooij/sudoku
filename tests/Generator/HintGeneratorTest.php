<?php

namespace Generator;

use CoenMooij\Sudoku\Generator\HintGenerator;
use CoenMooij\Sudoku\Puzzle\Grid;
use CoenMooij\Sudoku\Puzzle\Location;
use CoenMooij\Sudoku\Serializer\GridSerializer;
use PHPUnit\Framework\TestCase;

class HintGeneratorTest extends TestCase
{
    private const SERIALIZED_GRID = '820345701194728605753190402001879023289050106037610840068901057975200310000087964';
    private const LOCATIONS = [
        [0, 2], [0, 7], [1, 7], [2, 5], [2, 7], [3, 1], [3, 6], [4, 3], [4, 7], [5, 0],
        [5, 5], [5, 8], [6, 4], [6, 6], [7, 4], [7, 8], [8, 0], [8, 1], [8, 2], [8, 3],
    ];
    /**
     * @var HintGenerator
     */
    private $generator;

    /**
     * @var Grid
     */
    private $grid;

    public function setUp(): void
    {
        $this->generator = new HintGenerator();
        $this->grid = GridSerializer::deserialize(self::SERIALIZED_GRID);
    }

    /**
     * @test
     */
    public function generateOne(): void
    {
        $location = $this->generator->generateOne($this->grid);
        self::assertTrue(Location::match($location, new Location(0, 2)));
    }

    /**
     * @test
     */
    public function generateAll(): void
    {
        $locations = $this->generator->generateAll($this->grid);

        self::assertEquals(count(self::LOCATIONS), count($locations));

        foreach (self::LOCATIONS as $key => $data) {
            self::assertTrue(Location::match(new Location($data[0], $data[1]), $locations[$key]));
        }
    }
}
