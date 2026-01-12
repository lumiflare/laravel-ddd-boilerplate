<?php

declare(strict_types=1);

namespace App\Domain\User\Exception;

use InvalidArgumentException;

final class InvalidUserIdException extends InvalidArgumentException
{
    public function __construct(int $value)
    {
        parent::__construct(sprintf('Invalid user ID: %d. Must be a positive integer.', $value));
    }
}
