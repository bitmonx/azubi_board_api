<?php


namespace App\Domain\Listing\Service;


use App\Domain\Listing\Data\ListingData;
use App\Domain\Listing\Repository\ListingRepository;

/**
 * Class ListingSelector.
 * Handles listing selection.
 * @package App\Domain\Listing\Service
 */
final class ListingSelector
{

    /**
     * @var ListingRepository $repository
     */
    private $repository;

    /**
     * ListingSelector constructor.
     * @param ListingRepository $repository
     */
    public function __construct(ListingRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Selects the latest unfinished listing.
     * @return ListingData|null
     */
    public function getCurrentListing(): ?ListingData
    {
        return $this->repository->fetchCurrentListing();
    }

    /**
     * Selects all listing which are approved but not finished yet.
     * @return array
     */
    public function getUndoneCheckedListings(): array
    {
        return $this->repository->fetchUndoneCheckedListing();
    }

    /**
     * Selects all listings.
     * @return array
     */
    public function getAllListings(): array
    {
        return $this->repository->fetchAllListings();
    }

    /**
     * Selects the listing matching the given id.
     * @param int $id
     * @return ListingData|null
     */
    public function getListingById(int $id): ?ListingData
    {
        return $this->repository->fetchListingById($id);
    }

}
