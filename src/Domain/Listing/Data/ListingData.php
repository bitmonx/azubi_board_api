<?php

namespace App\Domain\Listing\Data;

/**
 * Class ListingData.
 * Helps storing listing data.
 * @package App\Domain\Listing\Data
 */
final class ListingData
{
    /**
     * @var int $id
     */
    public $id;

    /**
     * @var string $created_at
     */
    public $created_at;

    /**
     * @var int $created_by
     */
    public $created_by;

    /**
     * @var string $created_at
     */
    public $checked_at;

    /**
     * @var int $checked_by
     */
    public $checked_by;

    /**
     * @var string $done_at
     */
    public $done_at;

    /**
     * @var int $done_by
     */
    public $done_by;
}
