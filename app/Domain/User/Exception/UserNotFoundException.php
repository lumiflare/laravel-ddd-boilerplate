<?php

declare(strict_types=1);

namespace App\Domain\User\Exception;

use App\Domain\User\ValueObject\UserId;
use RuntimeException;

final class UserNotFoundException extends RuntimeException
{
    public function __construct(UserId $id)
    {
        parent::__construct(sprintf('User not found with ID: %s', $id->value()));
    }
}
