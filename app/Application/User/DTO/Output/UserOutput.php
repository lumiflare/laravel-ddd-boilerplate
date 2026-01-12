<?php

declare(strict_types=1);

namespace app\Application\User\DTO\Output;

use App\Domain\User\Entity\User;
use DateTimeImmutable;

readonly class UserOutput
{
    public function __construct(
        public ?int $id,
        public string $name,
        public string $email,
        public bool $emailVerified,
        public DateTimeImmutable $createdAt,
        public ?DateTimeImmutable $updatedAt,
    ) {}

    public static function fromEntity(User $user): self
    {
        return new self(
            id: $user->id()?->value(),
            name: $user->name()->value(),
            email: $user->email()->value(),
            emailVerified: $user->isEmailVerified(),
            createdAt: $user->createdAt(),
            updatedAt: $user->updatedAt(),
        );
    }
}
