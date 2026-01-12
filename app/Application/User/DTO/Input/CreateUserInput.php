<?php

declare(strict_types=1);

namespace app\Application\User\DTO\Input;

readonly class CreateUserInput
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    ) {}
}
