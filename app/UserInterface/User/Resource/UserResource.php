<?php

declare(strict_types=1);

namespace App\UserInterface\User\Resource;

use Override;
use app\Application\User\DTO\Output\UserOutput;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property UserOutput $resource
 */
final class UserResource extends JsonResource
{
    /** @return array<string, mixed> */
    #[Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'email_verified' => $this->resource->emailVerified,
            'created_at' => $this->resource->createdAt->format('c'),
            'updated_at' => $this->resource->updatedAt?->format('c'),
        ];
    }
}
