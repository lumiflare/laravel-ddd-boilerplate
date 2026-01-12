<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\HashedPassword;
use App\Domain\User\ValueObject\UserId;
use App\Domain\User\ValueObject\UserName;
use DateTimeImmutable;
use LogicException;

class User
{
    private function __construct(
        private ?UserId $id,
        private UserName $name,
        private Email $email,
        private HashedPassword $password,
        private ?DateTimeImmutable $emailVerifiedAt,
        private ?string $rememberToken,
        private readonly DateTimeImmutable $createdAt,
        private ?DateTimeImmutable $updatedAt = null,
    ) {}

    public static function create(
        UserName $name,
        Email $email,
        HashedPassword $password,
    ): self {
        return new self(
            id: null,
            name: $name,
            email: $email,
            password: $password,
            emailVerifiedAt: null,
            rememberToken: null,
            createdAt: new DateTimeImmutable,
        );
    }

    public static function reconstitute(
        UserId $id,
        UserName $name,
        Email $email,
        HashedPassword $password,
        ?DateTimeImmutable $emailVerifiedAt,
        ?string $rememberToken,
        DateTimeImmutable $createdAt,
        ?DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            id: $id,
            name: $name,
            email: $email,
            password: $password,
            emailVerifiedAt: $emailVerifiedAt,
            rememberToken: $rememberToken,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );
    }

    public function id(): ?UserId
    {
        return $this->id;
    }

    public function assignId(UserId $id): void
    {
        if ($this->id instanceof UserId) {
            throw new LogicException('User ID already assigned.');
        }

        $this->id = $id;
    }

    public function name(): UserName
    {
        return $this->name;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): HashedPassword
    {
        return $this->password;
    }

    public function emailVerifiedAt(): ?DateTimeImmutable
    {
        return $this->emailVerifiedAt;
    }

    public function rememberToken(): ?string
    {
        return $this->rememberToken;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function isEmailVerified(): bool
    {
        return $this->emailVerifiedAt instanceof DateTimeImmutable;
    }

    public function changeName(UserName $name): void
    {
        $this->name = $name;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function changeEmail(Email $email): void
    {
        if ($this->email->equals($email)) {
            return;
        }

        $this->email = $email;
        $this->emailVerifiedAt = null;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function changePassword(HashedPassword $password): void
    {
        $this->password = $password;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function verifyEmail(): void
    {
        $this->emailVerifiedAt = new DateTimeImmutable;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function updateRememberToken(?string $token): void
    {
        $this->rememberToken = $token;
    }
}
