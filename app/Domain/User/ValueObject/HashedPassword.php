<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use Stringable;

readonly class HashedPassword implements Stringable
{
    private function __construct(
        private string $value,
    ) {}

    public function __toString(): string
    {
        return $this->value;
    }

    public static function fromHash(string $hashedPassword): self
    {
        return new self($hashedPassword);
    }

    public static function fromPlainText(string $plainPassword): self
    {
        return new self(password_hash($plainPassword, PASSWORD_BCRYPT));
    }

    public function value(): string
    {
        return $this->value;
    }

    public function verify(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->value);
    }
}
