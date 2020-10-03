<?php


namespace App\Database;

use PDO;

/**
 * Class DatabaseProxy.
 * Sets up two different PDO connections
 * managed by the dependency injection container
 * @package App\Database
 */
final class DatabaseProxy
{
    /**
     * @var PDO $ems
     */
    private $ems;
    /**
     * @var PDO $azubi_board
     */
    private $azubi_board;

    /**
     * DatabaseProxy constructor.
     * @param PDO $ems
     * @param PDO $azubi_board
     */
    public function __construct(PDO $ems, PDO $azubi_board)
    {
        $this->ems = $ems;
        $this->azubi_board = $azubi_board;
    }

    /**
     * Getter for the ems PDO connection.
     * @return PDO
     */
    public function getEms(): PDO
    {
        return $this->ems;
    }

    /**
     * Getter for the azubi board PDO connection.
     * @return PDO
     */
    public function getAzubiBoard(): PDO
    {
        return $this->azubi_board;
    }
}
