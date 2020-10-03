<?php


namespace App\Domain\Item\Service;


use App\Domain\Item\Repository\ItemRepository;

/**
 * Class ItemModifier.
 * Handles item modification.
 * @package App\Domain\Item\Service
 */
final class ItemModifier
{

    /**
     * @var ItemRepository $repository
     */
    private $repository;

    /**
     * ItemModifier constructor.
     * @param ItemRepository $repository
     */
    public function __construct(ItemRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Updates the item matching the given id
     * with the given data using the repository.
     * @param int $id
     * @param string $description
     * @param string $deadline
     * @return bool
     */
    public function editItem(int $id, string $description, string $deadline): bool
    {
        return $this->repository->updateItem($id, $description, $deadline);
    }

}
