<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use Stringable;

readonly class Email implements Stringable
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
        $normalized = mb_strtolower(trim($value));

        return new self($normalized);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function domain(): string
    {
        return substr($this->value, strpos($this->value, '@') + 1);
    }
}
