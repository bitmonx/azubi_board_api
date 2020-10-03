<?php


namespace App\Domain\Listing\Service;


use App\Domain\Listing\Repository\ListingRepository;
use Cake\Chronos\Chronos;

/**
 * Class ListingModifier.
 * Handles listing modification.
 * @package App\Domain\Listing\Service
 */
final class ListingModifier
{

    /**
     * @var ListingRepository $repository
     */
    private $repository;

    /**
     * ListingModifier constructor.
     * @param ListingRepository $repository
     */
    public function __construct(ListingRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Approves listing matching the given id.
     * @param int $listId
     * @param int $userId
     * @return bool
     */
    public function approveListing(int $listId, int $userId): bool
    {
        $datetime = Chronos::now();
        return $this->repository->approveListing($listId, $userId, $datetime);
    }

    /**
     * Finishes the listing with the given id.
     * @param int $listId
     * @param int $userId
     * @return bool
     */
    public function finishListing(int $listId, int $userId): bool
    {
        $datetime = Chronos::now();
        return $this->repository->finishListing($listId, $userId, $datetime);
    }
}
