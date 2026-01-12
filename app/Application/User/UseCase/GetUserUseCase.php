<?php

declare(strict_types=1);

namespace App\Application\User\UseCase;

use app\Application\User\DTO\Output\UserOutput;
use App\Domain\User\Entity\User;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\ValueObject\UserId;

readonly class GetUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function execute(int $userId): UserOutput
    {
        $id = UserId::fromInt($userId);
        $user = $this->userRepository->findById($id);

        if (! $user instanceof User) {
            throw new UserNotFoundException($id);
        }

        return UserOutput::fromEntity($user);
    }
}
