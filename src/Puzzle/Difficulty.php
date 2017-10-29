<?php

declare(strict_types=1);

namespace CoenMooij\Sudoku\Puzzle;

use CoenMooij\Sudoku\Exception\InvalidDifficultyException;

class Difficulty
{
    public const VERY_EASY = 1;
    public const EASY = 2;
    public const NORMAL = 3;
    public const HARD = 4;
    public const LEGENDARY = 5;

    public const NUMBER_OF_HOLES_KEY = 'holes';
    public const BOUND_KEY = 'bound';

    private const LEVELS = [
        self::VERY_EASY => [self::NUMBER_OF_HOLES_KEY => 30, self::BOUND_KEY => 5],
        self::EASY => [self::NUMBER_OF_HOLES_KEY => 40, self::BOUND_KEY => 4],
        self::NORMAL => [self::NUMBER_OF_HOLES_KEY => 50, self::BOUND_KEY => 3],
        self::HARD => [self::NUMBER_OF_HOLES_KEY => 60, self::BOUND_KEY => 2],
        self::LEGENDARY => [self::NUMBER_OF_HOLES_KEY => 70, self::BOUND_KEY => 0],
    ];

    /**
     * @var int
     */
    private $difficulty;

    public function __construct(int $difficulty)
    {
        if (!isset(self::LEVELS[$difficulty])) {
            throw new InvalidDifficultyException();
        }
        $this->difficulty = $difficulty;
    }

    public function getNumberOfHoles(): int
    {
        $this->getParameter(self::NUMBER_OF_HOLES_KEY);
    }

    public function getBound(): int
    {
        $this->getParameter(self::BOUND_KEY);
    }

    private function getParameter(string $parameter): int
    {
        return self::LEVELS[$this->difficulty][$parameter];
    }
}
