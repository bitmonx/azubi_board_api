<?php


namespace App\Domain\User\Service;


use App\Domain\User\Repository\UserRepository;

/**
 * Class UserSelector.
 * Handles user selection.
 * @package App\Domain\User\Service
 */
final class UserSelector
{
    /**
     * @var UserRepository $repository
     */
    private $repository;

    /**
     * UserSelector constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     *Select the email addresses of all users matching the given department.
     * @param string $department
     * @return array
     */
    public function getAllEmailsByDepartment(string $department): array
    {
        return $this->repository->getAllEmailsByDepartment($department);
    }

    /**
     * Select the email addresses of all trainees.
     * @return array
     */
    public function getAllTraineeEmails(): array
    {
        return $this->repository->getAllTraineeEmails();
    }
}
