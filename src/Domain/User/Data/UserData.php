<?php

namespace App\Domain\User\Data;

/**
 * Class UserData.
 * Helps storing user data.
 * @package App\Domain\User\Data
 */
final class UserData
{
    /**
     * @var int $id
     */
    public $id;

    /**
     * @var string $username
     */
    public $username;

    /**
     * @var string $firstname
     */
    public $firstname;

    /**
     * @var string $name
     */
    public $name;

    /**
     * @var string $department
     */
    public $department;

    /**
     * @var boolean $isTrainee
     */
    public $isTrainee;
}
