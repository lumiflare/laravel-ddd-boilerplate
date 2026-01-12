<?php

declare(strict_types=1);

namespace App\Application\User\Exception;

use App\Domain\User\ValueObject\Email;
use RuntimeException;

final class EmailAlreadyExistsException extends RuntimeException
{
    public function __construct(Email $email)
    {
        parent::__construct(sprintf('Email already exists: %s', $email->value()));
    }
}
