<?php


namespace App\Domain\Listing\Repository;


use App\Database\DatabaseProxy;
use App\Domain\Listing\Data\ListingData;
use Cake\Chronos\Chronos;
use PDO;

/**
 * Class ListingRepository.
 * Manages interaction with listings database table.
 * @package App\Domain\Listing\Repository
 */
final class ListingRepository
{
    /**
     * @var PDO $connection
     */
    private $connection;

    /**
     * ListingRepository constructor.
     * @param DatabaseProxy $databaseProxy
     */
    public function __construct(DatabaseProxy $databaseProxy)
    {
        $this->connection = $databaseProxy->getAzubiBoard();
    }

    /**
     * Inserts a listing to the database table using the given data
     * and replies the id of the new table entry.
     * @param Chronos $created_at
     * @param int $userId
     * @return int
     */
    public function insertListing(Chronos $created_at, int $userId): int
    {
        $sql = "INSERT INTO ab_listings (created_at, created_by)
                VALUE (:created_at, :user_id)";

        $this->connection->prepare($sql)->execute([
            'created_at' => $created_at,
            'user_id' => $userId
        ]);

        return (int)$this->connection->lastInsertId();
    }

    /**
     * Removes the listing from the
     * database table matching the given id.
     * @param int $listingId
     * @return bool
     */
    public function deleteListing(int $listingId): bool
    {
        $sql = 'DELETE FROM ab_listings WHERE id = :id';

        return (bool)$this->connection->prepare($sql)->execute(['id' => $listingId]);
    }

    /**
     * Selects all listings from the database table.
     * @return array
     */
    public function fetchAllListings(): array
    {
        $sql = 'SELECT * FROM ab_listings';
        $statement = $this->connection->query($sql);
        $statement->setFetchMode(PDO::FETCH_CLASS, ListingData::class);
        return $statement->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * Selects the latest listing from the database table,#
     * which is not finished.
     * @return ListingData|null
     */
    public function fetchCurrentListing(): ?ListingData
    {
        $statement = $this->connection->query("
            SELECT * FROM ab_listings
            WHERE done_at IS NULL
            ORDER BY created_at DESC LIMIT 1");
        $statement->setFetchMode(PDO::FETCH_CLASS, ListingData::class);
        $listing =  $statement->fetch(PDO::FETCH_CLASS);
        return $listing === false ? null : $listing;
    }

    /**
     * Selects the listing from the database table matching the given id.
     * @param int $id
     * @return ListingData|null
     */
    public function fetchListingById(int $id): ?ListingData
    {
        $statement = $this->connection->prepare("
            SELECT *
            FROM ab_listings
            WHERE id = :id");
        $statement->execute(['id' => $id]);
        $statement->setFetchMode(PDO::FETCH_CLASS, ListingData::class);
        $listing =  $statement->fetch(PDO::FETCH_CLASS);
        return $listing === false ? null : $listing;
    }

    /**
     * Updates the listing matching the given id using the given data.
     * The listing now is approved for finishing.
     * @param int $listingId
     * @param int $userId
     * @param Chronos $datetime
     * @return bool
     */
    public function approveListing(int $listingId, int $userId, Chronos $datetime): bool{
        $sql = 'UPDATE ab_listings SET checked_at = :datetime, checked_by = :user
                WHERE id = :id';
        $statement = $this->connection->prepare($sql);
        return $statement->execute([
            'datetime' => $datetime,
            'user' => $userId,
            'id' => $listingId
        ]);
    }

    /**
     * Updates the listing matching the given id using the given data.
     * The listing now is finished.
     * @param int $listingId
     * @param int $userId
     * @param Chronos $datetime
     * @return bool
     */
    public function finishListing(int $listingId, int $userId, Chronos $datetime): bool{
        $sql = 'UPDATE ab_listings SET done_at = :datetime, done_by = :user
                WHERE id = :id';
        $statement = $this->connection->prepare($sql);
        return $statement->execute([
            'datetime' => $datetime,
            'user' => $userId,
            'id' => $listingId
        ]);
    }
}
