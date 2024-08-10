<?php

require_once 'ticTacToe.php';

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
