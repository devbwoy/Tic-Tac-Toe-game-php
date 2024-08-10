<?php

class TicTacToe
{
    private $debugMode;
    private $board;
    private $currentPlayer;
    private $moveCount = 0;

    protected function __construct()
    {
        $this->debugMode = true;
        $this->board = [
            [' ', ' ', ' '], // Top row
            [' ', ' ', ' '], // Middle row
            [' ', ' ', ' ']  // Bottom row
        ];
        $this->currentPlayer = 'X';
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
    }

    protected function displayBoardOld()
    {
        $no = 1;
        for ($i = 1; $i < 3; $i++) {
            for ($j = 1; $j < 3; $j++) {
                if ($no < 10) {
                    echo $no . "|" . ++$no . "|" . ++$no . "\n";
                    ++$no;
                }
                if ($i < 2) {
                    echo "-|-|-\n";
                }
            }
        }
    }

    function displayBoard()
    {
        $no = 1;
        echo "\nCurrent Board:\n\n";
        for ($i = 0; $i < 3; $i++) {
            // echo "  " . $this->board[$i][0] . " | " . $this->board[$i][1] . " | " . $this->board[$i][2] . "\n";

            $first = trim($this->board[$i][0]) ? $this->board[$i][0] : $no;
            $second = trim($this->board[$i][1]) ? $this->board[$i][1] : ++$no;
            $third = trim($this->board[$i][2]) ? $this->board[$i][2] : ++$no;

            echo "  " . $first . " | " . $second . " | " . $third . "\n";
            if ($i < 2) {
                echo " ---|---|---\n";
            }
            ++$no;
        }
        echo "\n";
    }

    function getMoveFromUser()
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
