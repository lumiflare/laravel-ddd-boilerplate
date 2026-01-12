<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use App\Domain\User\Exception\InvalidUserIdException;
use Stringable;

readonly class UserId implements Stringable
{
    private function __construct(
        private int $value,
    ) {}

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public static function fromInt(int $value): self
    {
        if ($value <= 0) {
            throw new InvalidUserIdException($value);
        }

        return new self($value);
    }

    public function value(): int
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
