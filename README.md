# Tic Tac Toe PHP Game

## Description

This is a simple implementation of the Tic Tac Toe game in PHP. It includes features such as:

- Two players taking turns (Player X and Player O).
- Displaying the current state of the board after each move.
- Detecting and announcing the winner or a draw.
- Handling invalid moves gracefully.
- Storing game results and moves in an SQLite database.

## Prerequisites

- PHP (version 7.4 or higher recommended)
- SQLite client for viewing the database
- Composer for running unit tests

## Running the PHP Script

1. **Navigate to the Project Directory**

   Open your terminal and navigate to the directory containing the `startGame.php` script:

   `cd /path/to/your/project`

2. **Run the PHP Script**

   Execute the following command to start the game:

   `php startGame.php`

   Follow the on-screen instructions to play the game.

## Viewing the Database

To view the SQLite database, you can use the SQLite Browser.

1. **Download SQLite Browser**

   Go to the SQLite Browser download page: [SQLite Browser Download](https://sqlitebrowser.org/dl/)

2. **Install SQLite Browser**

   Follow the installation instructions provided on the download page for your operating system.

3. **Open the Database**

   Use SQLite Browser to open the `tictactoe.db` file located in the project directory. This file contains the game results and moves.

## Running Unit Tests

1. **Install Composer**

   Make sure Composer is installed. If not, you can download it from [getcomposer.org](https://getcomposer.org/download/).

2. **Install Dependencies**

   In the project directory, run the following command to install PHP dependencies, including PHPUnit for unit testing:

   `composer require --dev phpunit/phpunit`

3. **Run Unit Tests**

   After installing the dependencies, execute the following command to run the unit tests:

   `vendor/bin/phpunit ticTacToeTest.php`

   Ensure that your test files are located in the `tests` directory and follow PHPUnit naming conventions.

## Contributing

Feel free to fork the repository, submit issues, or create pull requests with improvements or fixes.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
