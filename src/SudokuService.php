<?php

namespace CoenMooij\Sudoku;

class SudokuService
{
    /**
     * @var SimpleSolver
     */
    private $simpleSolver;

    public function __construct(SimpleSolver $simpleSolver)
    {
        $this->simpleSolver = $simpleSolver;
    }

    public function hint(): Cell
    {
        // todo implement
    }

    public function simpleSolve(Puzzle $sudokuPuzzle): Puzzle
    {
        // todo implement
    }

    public function generatePuzzle(Difficulty $difficulty)
    {
        $solutionGenerator = new SolutionGenerator();
        $solution = $solutionGenerator->generateSolution();

        $puzzleGenerator = new PuzzleGenerator();
        $puzzle = $puzzleGenerator->generatePuzzle($solution, $difficulty);

        return response()->json(
            [
                'puzzle' => $puzzle->getPuzzle(),
                'difficulty' => $difficulty
            ]
        );
    }

    /**
     * Controller method for the GET /solutions endpoint.
     * Checks if the solution is valid.
     *
     * @param Request $request The request.
     *
     * @return Response
     */
    public function checkSolution(Request $request)
    {
        $solution = $request->query('solution');
        if (strlen($solution) != 81 || !is_numeric($solution)) {
            throw new BadRequestHttpException('Invalid parameter `solution`.');
        }
        $sudokuParser = new SudokuParser();
        $sudokuGrid = $sudokuParser->parse($solution);

        $validator = new SudokuValidator();
        if ($validator->validate($sudokuGrid)) {
            $numberOfEmptyFields = $validator->numberOfEmptyFields($sudokuGrid);
            if ($numberOfEmptyFields > 0) {
                $message = "Going great! You still have " . $numberOfEmptyFields . " cells to fill.";
            } else {
                $message = "Perfect! How about a new game?";
            }
        } else {
            $message = "Oops! Looks like you made a mistake. Think you can find it without using reset?";
        }

        return response()->json(
            [
                'result' => $message
            ]
        );
    }
}
