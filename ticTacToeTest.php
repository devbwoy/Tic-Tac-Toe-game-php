<?php

require_once 'TicTacToe.php';

use PHPUnit\Framework\TestCase;

class TicTacToeTest extends TestCase
{
    private $ticTacToe;

    protected function setUp(): void
    {
        $this->ticTacToe = new TicTacToe();
    }

    public function testInitialBoardSetup()
    {
        $expectedBoard = [
            [' ', ' ', ' '],
            [' ', ' ', ' '],
            [' ', ' ', ' ']
        ];

        // Use reflection to access the private $board property
        $reflection = new ReflectionClass($this->ticTacToe);
        $property = $reflection->getProperty('board');
        $property->setAccessible(true);

        $actualBoard = $property->getValue($this->ticTacToe);

        $this->assertEquals($expectedBoard, $actualBoard, "Initial board setup is incorrect.");
    }

    public function testMakeMove()
    {
        // Make a move
        $move = 1; // Move to position 1
        $expectedBoard = [
            ['X', ' ', ' '],
            [' ', ' ', ' '],
            [' ', ' ', ' ']
        ];
        $expectedMoves = [
            [
                'player' => 'X',
                'row' => 0,
                'col' => 0
            ]
        ];

        // Use reflection to access private methods
        $reflection = new ReflectionClass($this->ticTacToe);
        $method = $reflection->getMethod('makeMove');
        $method->setAccessible(true);

        $method->invoke($this->ticTacToe, $move);

        // Access private properties
        $boardProperty = $reflection->getProperty('board');
        $boardProperty->setAccessible(true);
        $actualBoard = $boardProperty->getValue($this->ticTacToe);

        $movesProperty = $reflection->getProperty('moves');
        $movesProperty->setAccessible(true);
        $actualMoves = $movesProperty->getValue($this->ticTacToe);

        $this->assertEquals($expectedBoard, $actualBoard, "Board state after move is incorrect.");
        $this->assertEquals($expectedMoves, $actualMoves, "Moves array after move is incorrect.");
    }
}
