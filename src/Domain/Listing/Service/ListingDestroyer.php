<?php


namespace App\Domain\Listing\Service;

use App\Domain\Listing\Repository\ListingRepository;

/**
 * Class ListingDestroyer.
 * Handles listing deletion.
 * @package App\Domain\Listing\Service
 */
final class ListingDestroyer
{

    /**
     * @var ListingRepository $repository
     */
    private $repository;

    /**
     * ListingDestroyer constructor.
     * @param ListingRepository $repository
     */
    public function __construct(ListingRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Deletes the listing matching the given id.
     * @param int $listingId
     * @return bool
     */
    public function deleteListing(int $listingId): bool
    {
        return $this->repository->deleteListing($listingId);
    }

}
