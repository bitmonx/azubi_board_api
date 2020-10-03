<?php


namespace App\Domain\Item\Service;


use App\Domain\Item\Repository\ItemRepository;

/**
 * Class ItemDestroyer.
 * Handles item deletion.
 * @package App\Domain\Item\Service
 */
final class ItemDestroyer
{
    /**
     * @var ItemRepository $repository
     */
    private $repository;

    /**
     * ItemDestroyer constructor.
     * @param ItemRepository $repository
     */
    public function __construct(ItemRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Deletes the item with the given id using the
     * repository and replies a boolean representing
     * whether it was successful or not.
     * @param int $id
     * @return bool
     */
    public function deleteItem(int $id): bool
    {
        return $this->repository->deleteItem($id);
    }
}
