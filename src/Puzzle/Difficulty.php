<?php

namespace CoenMooij\Sudoku\Puzzle;

class Difficulty
{
    public const VERY_EASY = 1;
    public const EASY = 2;
    public const NORMAL = 3;
    public const HARD = 4;
    public const LEGENDARY = 5;

    // todo fix
    const DIFFICULTY_LEVELS = [
        ['level' => 1, 'holes' => 30, 'bound' => 5],
        ['level' => 2, 'holes' => 40, 'bound' => 4],
        ['level' => 3, 'holes' => 50, 'bound' => 3],
        ['level' => 4, 'holes' => 60, 'bound' => 2],
        ['level' => 5, 'holes' => 70, 'bound' => 0],
    ];
}
