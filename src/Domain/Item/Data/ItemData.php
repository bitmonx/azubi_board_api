<?php


namespace App\Domain\Item\Data;

/**
 * Class ItemData.
 * Helps storing item data.
 * @package App\Domain\Item\Data
 */
final class ItemData
{
    /**
     * @var int $id
     */
    public $id;

    /**
     * @var string $description
     */
    public $description;

    /**
     * @var string $created_at
     */
    public $created_at;

    /**
     * @var string $created_by
     */
    public $created_by;

    /**
     * @var string $deadline
     */
    public $deadline;
}
