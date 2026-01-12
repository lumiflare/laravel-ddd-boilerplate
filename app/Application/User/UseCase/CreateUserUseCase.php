<?php

declare(strict_types=1);

namespace App\Application\User\UseCase;

use app\Application\User\DTO\Input\CreateUserInput;
use app\Application\User\DTO\Output\UserOutput;
use App\Application\User\Exception\EmailAlreadyExistsException;
use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\HashedPassword;
use App\Domain\User\ValueObject\UserName;

readonly class CreateUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function execute(CreateUserInput $input): UserOutput
    {
        $email = Email::fromString($input->email);

        if ($this->userRepository->existsByEmail($email)) {
            throw new EmailAlreadyExistsException($email);
        }

        $user = User::create(
            name: UserName::fromString($input->name),
            email: $email,
            password: HashedPassword::fromPlainText($input->password),
        );

        $this->userRepository->save($user);

        return UserOutput::fromEntity($user);
    }
}
