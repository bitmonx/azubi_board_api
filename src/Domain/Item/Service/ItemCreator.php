<?php


namespace App\Domain\Item\Service;


use App\Domain\Item\Repository\ItemRepository;
use Cake\Chronos\Chronos;

/**
 * Class ItemCreator.
 * Handles item creation.
 * @package App\Domain\Item\Service
 */
final class ItemCreator
{

    /**
     * @var ItemRepository $repository
     */
    private $repository;

    /**
     * ItemCreator constructor.
     * @param ItemRepository $repository
     */
    public function __construct(ItemRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Creates an item, inserts it to the database table using the
     * repository and replies the id of the new table entry.
     * @param int $listingId
     * @param string $description
     * @param int $userId
     * @param string $deadline
     * @return int
     */
    public function addItem(int $listingId,
                            string $description,
                            int $userId,
                            string $deadline)
    : int
    {

        $deadline = substr($deadline, 0, 10);
        $created_at = Chronos::now();
        return $this->repository->insertItem($listingId, $description, $created_at, $userId, $deadline);
    }

}
