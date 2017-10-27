<?php

declare(strict_types=1);

namespace CoenMooij\Sudoku\Puzzle;

use CoenMooij\Sudoku\Exception\InvalidValueException;

final class Cell
{
    public const POSSIBLE_VALUES = [1, 2, 3, 4, 5, 6, 7, 8, 9];
    public const EMPTY_VALUE = 0;

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
     *
     * @throws InvalidValueException
     */
    public function setValue(int $value): void
    {
        if (!self::isValidValue($value)) {
            throw new InvalidValueException();
        }
        $this->value = $value;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->value === self::EMPTY_VALUE;
    }

    /**
     * @param int $value
     *
     * @return bool
     */
    public static function isValidValue(int $value): bool
    {
        return in_array($value, self::POSSIBLE_VALUES, true);
    }
}
