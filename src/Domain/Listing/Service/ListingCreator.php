<?php


namespace App\Domain\Listing\Service;

use App\Domain\Listing\Repository\ListingRepository;
use Cake\Chronos\Chronos;

/**
 * Class ListingCreator.
 * Handles listing creation.
 * @package App\Domain\Listing\Service
 */
final class ListingCreator
{
    /**
     * @var ListingRepository $repository
     */
    private $repository;

    /**
     * The constructor.
     * @param ListingRepository $repository The repository
     */
    public function __construct(
        ListingRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Create a new listing.
     * @param int $userId
     * @return int The new listing ID
     */
    public function createListing(int $userId): int
    {
        $created_at = Chronos::now();

        return $this->repository->insertListing($created_at, $userId);
    }
}
