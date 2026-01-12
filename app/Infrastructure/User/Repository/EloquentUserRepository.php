<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Repository;

use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\HashedPassword;
use App\Domain\User\ValueObject\UserId;
use App\Domain\User\ValueObject\UserName;
use App\Infrastructure\User\Eloquent\UserEloquent;
use DateTimeImmutable;
use Illuminate\Support\Carbon;

final readonly class EloquentUserRepository implements UserRepositoryInterface
{
    public function save(User $user): void
    {
        // create
        if (! $user->id() instanceof UserId) {
            $model = new UserEloquent;
            $model->name = $user->name()->value();
            $model->email = $user->email()->value();
            $model->password = $user->password()->value();
            $model->email_verified_at = $this->toCarbon($user->emailVerifiedAt());
            $model->remember_token = $user->rememberToken();
            $model->save();
            $user->assignId(UserId::fromInt($model->id));

            return;
        }

        // update
        UserEloquent::query()
            ->where('id', $user->id()->value())
            ->update([
                'name' => $user->name()->value(),
                'email' => $user->email()->value(),
                'password' => $user->password()->value(),
                'email_verified_at' => $this->toCarbon($user->emailVerifiedAt()),
                'remember_token' => $user->rememberToken(),
            ]);
    }

    public function findById(UserId $id): ?User
    {
        $model = UserEloquent::query()->find($id->value());

        if ($model === null) {
            return null;
        }

        return $this->toDomainEntity($model);
    }

    public function findByEmail(Email $email): ?User
    {
        $model = UserEloquent::query()
            ->where('email', $email->value())
            ->first();

        if ($model === null) {
            return null;
        }

        return $this->toDomainEntity($model);
    }

    public function delete(User $user): void
    {
        if (! $user->id() instanceof UserId) {
            return;
        }

        UserEloquent::query()
            ->where('id', $user->id()->value())
            ->delete();
    }

    public function existsByEmail(Email $email): bool
    {
        return UserEloquent::query()
            ->where('email', $email->value())
            ->exists();
    }

    private function toDomainEntity(UserEloquent $model): User
    {
        return User::reconstitute(
            id: UserId::fromInt($model->id),
            name: UserName::fromString($model->name),
            email: Email::fromString($model->email),
            password: HashedPassword::fromHash($model->password),
            emailVerifiedAt: $model->email_verified_at
                ? new DateTimeImmutable($model->email_verified_at->toDateTimeString())
                : null,
            rememberToken: $model->remember_token,
            createdAt: new DateTimeImmutable($model->created_at->toDateTimeString()),
            updatedAt: $model->updated_at
                ? new DateTimeImmutable($model->updated_at->toDateTimeString())
                : null,
        );
    }

    private function toCarbon(?DateTimeImmutable $dateTime): ?Carbon
    {
        if (! $dateTime instanceof DateTimeImmutable) {
            return null;
        }

        return Carbon::instance($dateTime);
    }
}
