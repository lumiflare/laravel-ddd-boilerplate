<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use App\Domain\User\Repository\UserRepositoryInterface;
use App\Infrastructure\User\Repository\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;
use Override;

final class UserServiceProvider extends ServiceProvider
{
    /** @var array<class-string, class-string> */
    public array $bindings = [
        UserRepositoryInterface::class => EloquentUserRepository::class,
    ];

    #[Override]
    public function register(): void {}
}
