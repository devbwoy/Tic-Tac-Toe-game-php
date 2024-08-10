<?php

// Include the TicTacToeDatabase trait, which handles database connection and setup
require_once 'TicTacToeDatabase.php';

class TicTacToe
{
    use TicTacToeDatabase; // Use the TicTacToeDatabase trait to manage database operations

    private $debugMode; // Flag to indicate whether the game is in debug mode
    private $board; // The 3x3 Tic-Tac-Toe board
    private $currentPlayer; // Tracks the current player ('X' or 'O')
    private $moveCount; // Tracks the number of moves made in the game
    private $moves; // An array to store all moves made during the game

    // Constant array that maps move numbers (1-9) to board positions (row, col)
    private const MOVE_TO_POSITION = [
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

    // Constructor to initialize the game and the database
    protected function __construct()
    {
        $this->initializeDatabase(); // Initialize the database connection
        $this->createTables(); // Create the necessary tables if they don't exist

        // Set up initial game state
        $this->debugMode = true;
        $this->board = [
            [' ', ' ', ' '], // Top row
            [' ', ' ', ' '], // Middle row
            [' ', ' ', ' ']  // Bottom row
        ];

        $this->currentPlayer = 'X'; // First player to make a move
        $this->moveCount = 0; // Initialize move count
        $this->moves = []; // Initialize moves array
        $this->clearScreen(); // Clear the screen for a fresh start
        $this->displayWelcomeMessage(); // Display the welcome message
    }

    // Function to create the necessary database tables
    private function createTables()
    {
        // SQL statement to create game_result table
        $createGameResultTable = "
            CREATE TABLE IF NOT EXISTS game_result (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                winner TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
        ";

        // SQL statement to create game_moves table with row and col instead of move_position
        $createGameMovesTable = "
            CREATE TABLE IF NOT EXISTS game_moves (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                game_id INTEGER NOT NULL,
                player TEXT NOT NULL,
                row INTEGER NOT NULL,
                col INTEGER NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (game_id) REFERENCES game_result(id)
            );
        ";

        // Execute the SQL statements to create the tables
        $this->db->exec($createGameResultTable);
        $this->db->exec($createGameMovesTable);
    }

    // Function to insert a game result into the game_result table
    public function insertGameResult($winner)
    {
        $stmt = $this->db->prepare("INSERT INTO game_result (winner) VALUES (:winner)");
        $stmt->bindParam(':winner', $winner);
        $stmt->execute();

        // Return the last inserted game ID
        return $this->db->lastInsertId();
    }

    // Function to insert multiple game moves into the game_moves table
    public function insertGameMoves($gameId, $gameMoves)
    {
        $values = [];
        $params = [];
        foreach ($gameMoves as $move) {
            $values[] = "(?, ?, ?, ?)";
            $params[] = $gameId;
            $params[] = $move['player'];
            $params[] = $move['row'];
            $params[] = $move['col'];
        }

        // SQL statement for bulk insert of game moves
        $sql = "INSERT INTO game_moves (game_id, player, row, col) VALUES " . implode(", ", $values);
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
    }

    // Error handling function
    private function handleError($exception)
    {
        if ($this->debugMode) {
            // Display error message in debug mode
            echo "Debug: " . $exception->getMessage() . "\n";
        } else {
            // Log the error or perform other non-debug actions
        }
    }

    // Function to clear the console screen
    private function clearScreen()
    {
        try {
            // Clear screen command based on operating system
            if (strpos(PHP_OS, 'WIN') !== false) {
                system('cls'); // Windows
            } else {
                system('clear'); // Unix-based systems
            }
        } catch (Exception $e) {
            // Handle any errors during screen clearing
            $this->handleError($e);
        }
    }

    // Function to display the welcome message
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

    // Function to wait for user to press Enter
    private function waitForKeypress()
    {
        echo "Press Enter to continue...";
        fgets(STDIN);
    }

    // Function to start the game loop
    protected function startGame()
    {
        $this->waitForKeypress(); // Wait for user input to start the game
        $this->displayBoard(); // Display the initial empty board

        while (true) {
            $move = $this->getMoveFromUser(); // Get a move from the current player
            if ($this->makeMove($move)) { // Make the move and check if it's valid
                $this->displayBoard(); // Display the board after the move
                ++$this->moveCount; // Increment the move count
                if ($this->moveCount >= 5 && $this->isPlayerWin()) { // Check for a win condition
                    $gameId = $this->insertGameResult($this->currentPlayer); // Save the result if the player wins
                    $this->insertGameMoves($gameId, $this->moves); // Save all moves of the game
                    echo "Player " . $this->currentPlayer . " wins!\n";
                    break; // Exit the game loop if there is a winner
                }
                $this->togglePlayer(); // Switch the current player
            }

            if ($this->moveCount >= 9) { // Check for a draw condition
                echo "It's a draw!\n";
                break; // Exit the game loop if it's a draw
            }
        }
    }

    // Function to display the current state of the board
    private function displayBoard()
    {
        $no = 1; // Counter for board positions
        echo "\nCurrent Board:\n\n";
        for ($i = 0; $i < 3; $i++) {

            // Display either the move (X or O) or the board position number
            $first = trim($this->board[$i][0]) ? $this->board[$i][0] : $no;
            ++$no;
            $second = trim($this->board[$i][1]) ? $this->board[$i][1] : $no;
            ++$no;
            $third = trim($this->board[$i][2]) ? $this->board[$i][2] : $no;

            // Print the row
            echo "  " . $first . " | " . $second . " | " . $third . "\n";
            if ($i < 2) {
                echo " ---|---|---\n"; // Print the separator between rows
            }
            ++$no;
        }
        echo "\n";
    }

    // Function to get a valid move from the user
    private function getMoveFromUser()
    {
        while (true) {
            echo "Player " . $this->currentPlayer . ", enter your move (1-9): ";
            $input = trim(fgets(STDIN)); // Read user input
            if (!empty($input) && is_numeric($input)) {
                $move = (int)$input; // Convert input to an integer
                if ($move >= 1 && $move <= 9) {
                    return $move; // Return the move if it's valid
                }
            }
            echo "Invalid move. Please enter a number between 1 and 9.\n";
        }
    }

    // Function to make a move on the board
    private function makeMove($move)
    {
        // Map the move to a board position
        $position = self::MOVE_TO_POSITION[$move];
        $row = $position['row'];
        $col = $position['col'];

        // Check if the board position is already occupied
        if ($this->board[$row][$col] === ' ') {
            // Mark the board with the current player's symbol
            $this->board[$row][$col] = $this->currentPlayer;
            $this->moves[] = [
                'player' => $this->currentPlayer,
                'row' => $row,
                'col' => $col
            ];
            return true; // Move was successful
        } else {
            echo "That position is already occupied. Try again.\n";
            return false; // Move was not successful
        }
    }

    // Function to toggle between players
    private function togglePlayer()
    {
        // Switch the current player
        $this->currentPlayer = $this->currentPlayer === 'X' ? 'O' : 'X';
    }

    // Function to check if the current player has won
    private function isPlayerWin()
    {
        // Check rows, columns, and diagonals for a win condition
        return $this->checkRowsForWin() || $this->checkColumnsForWin() || $this->checkDiagonalsForWin();
    }

    // Function to check if any row has all the same player's symbols
    private function checkRowsForWin()
    {
        for ($i = 0; $i < 3; $i++) {
            if (
                $this->board[$i][0] === $this->currentPlayer &&
                $this->board[$i][1] === $this->currentPlayer &&
                $this->board[$i][2] === $this->currentPlayer
            ) {
                return true;
            }
        }
        return false;
    }

    // Function to check if any column has all the same player's symbols
    private function checkColumnsForWin()
    {
        for ($i = 0; $i < 3; $i++) {
            if (
                $this->board[0][$i] === $this->currentPlayer &&
                $this->board[1][$i] === $this->currentPlayer &&
                $this->board[2][$i] === $this->currentPlayer
            ) {
                return true;
            }
        }
        return false;
    }

    // Function to check if either diagonal has all the same player's symbols
    private function checkDiagonalsForWin()
    {
        // Check the main diagonal and the anti-diagonal
        return ($this->board[0][0] === $this->currentPlayer &&
            $this->board[1][1] === $this->currentPlayer &&
            $this->board[2][2] === $this->currentPlayer) ||
            ($this->board[0][2] === $this->currentPlayer &&
                $this->board[1][1] === $this->currentPlayer &&
                $this->board[2][0] === $this->currentPlayer);
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
