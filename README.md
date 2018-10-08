[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/coenmooij/sudoku/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/coenmooij/sudoku/?branch=master) [![Code Intelligence Status](https://scrutinizer-ci.com/g/coenmooij/sudoku/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence) [![Build Status](https://travis-ci.org/coenmooij/sudoku.svg?branch=master)](https://travis-ci.org/coenmooij/sudoku)

# sudoku
Library for generating, validating, solving sudoku puzzles

## Composer
To use this package, simply run `composer require coenmooij/sudoku`.

## Usage

### SudokuService
Exposes the main functionality of the library in a service.

### Solvers

#### BacktrackSolver
Solves any solvable grid using an informed backtrack algorithm. Throws an `UnsolvableException` if it fails.

#### SimpleSolver
Solves the grid using only row, column and block checks. Will return the (partially) completed `Grid`.

### Generators

#### SolutionGenerator
Generates a complete `Grid`

#### PuzzleGenerator
Creates a puzzle based on a complete `Grid` and a `Difficulty`
Difficulty ranges from 1-5 and can be accessed by a constant. e.g. `Difficulty::EASY`.

#### HintGenerator
From a grid it creates hints. It returns locations which can be solved by row, column & block checks.
You can either get a random one or all of them.

### GridSerializer
`GridSerializer` is a simple serializer that turns a 81 character string into a `Grid` and back.
e.g. `642957138719843652538126794483712569976538421125694387294361875357489216861275943`
Empty values will be serialized to `0` but the deserializer also accepts alphabetical characters as empty values. 

### Validator

#### DigValidator
Helper class to see if it is 'safe' to dig out the value at a certain `Location`. It checks whether the `Grid` is still uniquely solvable.

#### GridValidator
Simple utility class to check if all (row, column and block) constraints are still met.
