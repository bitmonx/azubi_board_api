<?php


namespace App\Domain\Item\Repository;


use App\Database\DatabaseProxy;
use App\Domain\Item\Data\ItemData;
use Cake\Chronos\Chronos;
use PDO;

/**
 * Class ItemRepository.
 * Manages interaction with items database table.
 * @package App\Domain\Item\Repository
 */
final class ItemRepository
{
    /**
     * @var PDO $connection
     */
    private $connection;

    /**
     * ItemRepository constructor.
     * @param DatabaseProxy $proxy
     */
    public function __construct(DatabaseProxy $proxy)
    {
        $this->connection = $proxy->getAzubiBoard();
    }

    /**
     * Inserts an entry to the database table using the given params.
     * @param int $listingId
     * @param string $description
     * @param Chronos $created_at
     * @param int $userId
     * @param string $deadline
     * @return int
     */
    public function insertItem(
        int $listingId,
        string $description,
        Chronos $created_at,
        int $userId,
        string $deadline): int
    {
        $sql = "INSERT INTO ab_items (listing_id, description, created_at, created_by, deadline)
                VALUE (:listing, :description, :created_at, :user, :deadline)";

        $this->connection->prepare($sql)->execute([
            'listing' => $listingId,
            'description' => $description,
            'created_at' => $created_at,
            'user' => $userId,
            'deadline' => $deadline
        ]);

        return (int)$this->connection->lastInsertId();
    }

    /**
     * Deletes the element with the given id.
     * @param int $id
     * @return bool
     */
    public function deleteItem(int $id): bool
    {
        $sql = 'DELETE FROM ab_items WHERE id = :id';
        $statement = $this->connection->prepare($sql);
        return (bool)$statement->execute(['id' => $id]);
    }

    /**
     * Selects all items from the database table.
     * @return array
     */
    public function fetchAllItems(): array
    {
        $sql = 'SELECT * FROM ab_items';
        $statement = $this->connection->query($sql);
        $statement->setFetchMode(PDO::FETCH_CLASS, ItemData::class);
        return $statement->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * Selects all items matching the given listing id.
     * @param int $listingId
     * @return array
     */
    public function fetchItemsByListing(int $listingId): array
    {
        $sql = '
                SELECT * FROM ab_items WHERE listing_id = :id';
        $statement = $this->connection->prepare($sql);
        $statement->execute(['id' => $listingId]);
        $statement->setFetchMode(PDO::FETCH_CLASS, ItemData::class);
        return $statement->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * Selects all items matching the given id.
     * @param int $id
     * @return ItemData|null
     */
    public function fetchItemById(int $id): ?ItemData
    {
        $sql = 'SELECT * FROM ab_items WHERE id = :id';
        $statement = $this->connection->prepare($sql);
        $statement->execute(['id' => $id]);
        $statement->setFetchMode(PDO::FETCH_CLASS, ItemData::class);
        $item = $statement->fetch(PDO::FETCH_CLASS);
        return $item === false ? null : $item;
    }

    /**
     * Updates the item matching the given id using the given data.
     * @param int $id
     * @param string $description
     * @param string $deadline
     * @return bool
     */
    public function updateItem(int $id, string $description, string $deadline): bool
    {
        $sql = 'UPDATE ab_items SET description = :description, deadline = :deadline WHERE id = :id';
        $statement = $this->connection->prepare($sql);
        return $statement->execute([
            'id' => $id,
            'description' => $description,
            'deadline' => $deadline]);
    }
}
