<?php
require_once 'ticTacToe.php';

use PHPUnit\Framework\TestCase;

class TicTacToeTest extends TestCase
{
    public function testStartGame()
    {
        $game = new TicTacToe();
        $game->startGame();

        // Verify that the game starts with an empty board
        $expectedBoard = [
            [' ', ' ', ' '],
            [' ', ' ', ' '],
            [' ', ' ', ' '],
        ];
        $this->assertEquals($expectedBoard, $game->board);

        // Verify that the current player is 'X'
        $this->assertEquals('X', $game->currentPlayer);

        // Verify that the move count is 0
        $this->assertEquals(0, $game->moveCount);
    }
}
