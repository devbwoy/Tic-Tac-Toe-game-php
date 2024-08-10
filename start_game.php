<?php

class TicTacToe
{
    private $debugMode;
    private $board;
    private $currentPlayer;
    private $moveCount;
    private $moves;

    private $moveToPosition = [
        1 => ['row' => 0, 'col' => 0],
        2 => ['row' => 0, 'col' => 1],
        3 => ['row' => 0, 'col' => 2],
        4 => ['row' => 1, 'col' => 0],
        5 => ['row' => 1, 'col' => 1],
        6 => ['row' => 1, 'col' => 2],
        7 => ['row' => 2, 'col' => 0],
        8 => ['row' => 2, 'col' => 1],
        9 => ['row' => 2, 'col' => 2],
    ];

    protected function __construct()
    {
        $this->debugMode = true;
        $this->board = [
            [' ', ' ', ' '], // Top row
            [' ', ' ', ' '], // Middle row
            [' ', ' ', ' ']  // Bottom row
        ];

        $this->currentPlayer = 'X'; // first user to make a move
        $this->moveCount = 0;
        $this->moves = [];
        $this->clearScreen();
        $this->displayWelcomeMessage();
    }

    private function handleError($exception)
    {
        if ($this->debugMode) {
            echo "Debug: " . $exception->getMessage() . "\n";
        } else {
            // Log the error or perform other non-debug actions
        }
    }

    private function clearScreen()
    {
        try {
            if (strpos(PHP_OS, 'WIN') !== false) {
                system('cls');
            } else {
                system('clear');
            }
        } catch (Exception $e) {
            $this->handleError($e);
        }
    }

    private function displayWelcomeMessage()
    {
        echo "\n\n";
        echo "  ╔══════════════════════════════════════════════╗\n";
        echo "  ║                                              ║\n";
        echo "  ║             Welcome to Tic-Tac-Toe!          ║\n";
        echo "  ║                                              ║\n";
        echo "  ╚══════════════════════════════════════════════╝\n\n";
        echo "  Rules:\n";
        echo "  - Two players take turns marking a space on a 3x3 grid.\n";
        echo "  - The first player to get three of their marks in a row (horizontally, vertically, or diagonally) wins.\n";
        echo "  - If all spaces are filled and no player has three in a row, it's a draw.\n\n";
        // echo "  Press Enter to start the game.\n";
    }

    private function waitForKeypress()
    {
        echo "Press Enter to continue...";
        fgets(STDIN);
    }

    protected function startGame()
    {
        $this->waitForKeypress();
        $this->displayBoard();
        while (true) {
            $move = $this->getMoveFromUser(); // numeric value 
            if ($this->makeMove($move)) {
                $this->displayBoard();
                ++$this->moveCount;
                if ($this->moveCount >= 5 && $this->isPlayerWin()) {
                    echo "Player " . $this->currentPlayer . " wins!\n";
                    break;
                }
                $this->togglePlayer();
            }

            if ($this->moveCount >= 9) {
                echo "It's a draw!\n";
                break;
            }
        }
    }

    private function displayBoard()
    {
        $no = 1;
        echo "\nCurrent Board:\n\n";
        for ($i = 0; $i < 3; $i++) {

            $first = trim($this->board[$i][0]) ? $this->board[$i][0] : $no;
            ++$no;
            $second = trim($this->board[$i][1]) ? $this->board[$i][1] : $no;
            ++$no;
            $third = trim($this->board[$i][2]) ? $this->board[$i][2] : $no;

            echo "  " . $first . " | " . $second . " | " . $third . "\n";
            if ($i < 2) {
                echo " ---|---|---\n";
            }
            ++$no;
        }
        echo "\n";
    }

    private function makeMove($moveNo)
    {

        $movePositionDetail = $this->moveToPosition[$moveNo];
        $row = $movePositionDetail['row'];
        $col = $movePositionDetail['col'];

        if ($this->board[$row][$col] === ' ') {
            $this->board[$row][$col] = $this->currentPlayer;
            $this->moves[] = ['player' => $this->currentPlayer, 'row' => $row, 'col' => $col];
            return true;
        } else {
            echo "That position is already taken! Choose another one.\n";
            return false;
        }
    }

    private function getMoveFromUser()
    {
        while (true) {
            echo "Player " . $this->currentPlayer . ", enter your move (1 2 ... 9): ";
            $input = trim(fgets(STDIN));
            if (is_numeric($input) && (int)$input >= 1 && (int)$input <= 9) {
                return (int)$input;
            } else {
                echo "Invalid input! Please enter a number between 1 and 9.\n";
            }
        }
    }

    private function togglePlayer()
    {
        $this->currentPlayer = $this->currentPlayer == 'X' ? 'O' : 'X';
    }

    private function isPlayerWin()
    {
        // Check rows and columns
        for ($i = 0; $i < 3; $i++) {
            if (($this->board[$i][0] !== ' ' && $this->board[$i][0] === $this->board[$i][1] && $this->board[$i][1] === $this->board[$i][2]) ||
                ($this->board[0][$i] !== ' ' && $this->board[0][$i] === $this->board[1][$i] && $this->board[1][$i] === $this->board[2][$i])
            ) {
                return true;
            }
        }

        // Check diagonals
        if (($this->board[0][0] !== ' ' && $this->board[0][0] === $this->board[1][1] && $this->board[1][1] === $this->board[2][2]) ||
            ($this->board[0][2] !== ' ' && $this->board[0][2] === $this->board[1][1] && $this->board[1][1] === $this->board[2][0])
        ) {
            return true;
        }

        return false;
    }
}

class TicTacToeExtended extends TicTacToe
{

    function __construct()
    {
        parent::__construct();
    }

    public function initiateGame()
    {
        $this->startGame();
    }
}

$obj = new TicTacToeExtended();
$obj->initiateGame();
