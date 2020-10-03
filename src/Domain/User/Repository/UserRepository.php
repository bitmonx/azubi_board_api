<?php


namespace App\Domain\User\Repository;


use App\Database\DatabaseProxy;
use App\Domain\User\Data\UserData;
use PDO;

/**
 * Class UserRepository.
 * Manages interaction with the user database table.
 * @package App\Domain\User\Repository
 */
final class UserRepository
{
    /**
     * @var PDO $connection
     */
    private $connection;

    /**
     * UserRepository constructor.
     * @param DatabaseProxy $databaseProxy
     */
    public function __construct(DatabaseProxy $databaseProxy)
    {
        $this->connection = $databaseProxy->getEms();
    }

    /**
     * Selects the user matching the given username.
     * @param string $username
     * @return UserData|null
     */
    public function getUserByUsername(string $username): ?UserData
    {
        $statement = $this->connection->prepare("
            SELECT id, loginname as username, name, vorname as firstname, abteilung as department, azubi as isTrainee
            FROM benutzer
            WHERE loginname = :username");
        $statement->execute(['username' => $username]);
        $statement->setFetchMode(PDO::FETCH_CLASS, UserData::class);
        return $statement->fetch(PDO::FETCH_CLASS);
    }

    /**
     * Selects the email addresses of all users
     * matching the given department.
     * @param string $department
     * @return array
     */
    public function getAllEmailsByDepartment(string $department): array
    {
        $sql = "SELECT email FROM benutzer WHERE abteilung = :department AND email IS NOT NULL";
        $statement = $this->connection->prepare($sql);
        $statement->execute(['department' => $department]);
        return $statement->fetchAll();
    }

    /**
     * Selects the email addresses of all trainees.
     * @return array
     */
    public function getAllTraineeEmails(): array
    {
        $sql = "SELECT email FROM benutzer WHERE azubi = 'Y' AND email IS NOT NULL";
        $statement = $this->connection->query($sql);
        return $statement->fetchAll();
    }
}
