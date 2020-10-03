<?php


namespace App\Domain\Item\Service;


use App\Domain\Item\Data\ItemData;
use App\Domain\Item\Repository\ItemRepository;

/**
 * Class ItemSelector.
 * Handles item selection.
 * @package App\Domain\Item\Service
 */
final class ItemSelector
{

    /**
     * @var ItemRepository $repository
     */
    private $repository;

    /**
     * ItemSelector constructor.
     * @param ItemRepository $repository
     */
    public function __construct(ItemRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Selects all items using the repository.
     * @return array
     */
    public function getAllItems(): array
    {
        return $this->repository->fetchAllItems();
    }

    /**
     * Selects all items matching the given listing id
     * using the repository.
     * @param int $listingId
     * @return array
     */
    public function getAllItemsByListing(int $listingId): array
    {
        return $this->repository->fetchItemsByListing($listingId);
    }

    /**
     * Selects the item matching the given id
     * using the repository.
     * @param int $id
     * @return ItemData|null
     */
    public function getItemById(int $id): ?ItemData
    {
        return $this->repository->fetchItemById($id);
    }
}
