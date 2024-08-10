<?php

trait TicTacToeDatabase
{
    protected $db;
    private $dbFile = 'tictactoe.db';

    // Method to initialize the database connection
    protected function initializeDatabase()
    {
        // Create or open the SQLite database file
        $this->db = new PDO("sqlite:" . $this->dbFile);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}
