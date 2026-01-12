<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use Stringable;

readonly class UserName implements Stringable
{
    private function __construct(
        private string $value,
    ) {}

    public function __toString(): string
    {
        return $this->value;
    }

    public static function fromString(string $value): self
    {
        $trimmed = trim($value);

        return new self($trimmed);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
