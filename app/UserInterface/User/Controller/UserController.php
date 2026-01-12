<?php

declare(strict_types=1);

namespace App\UserInterface\User\Controller;

use app\Application\User\DTO\Input\CreateUserInput;
use App\Application\User\Exception\EmailAlreadyExistsException;
use App\Application\User\UseCase\CreateUserUseCase;
use App\Application\User\UseCase\GetUserUseCase;
use App\Domain\User\Exception\UserNotFoundException;
use App\UserInterface\User\Request\CreateUserRequest;
use App\UserInterface\User\Resource\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

final class UserController extends Controller
{
    public function __construct(
        private readonly CreateUserUseCase $createUserUseCase,
        private readonly GetUserUseCase $getUserUseCase,
    ) {}

    public function store(CreateUserRequest $request): JsonResponse
    {
        try {
            $input = new CreateUserInput(
                name: $request->validated('name'),
                email: $request->validated('email'),
                password: $request->validated('password'),
            );

            $output = $this->createUserUseCase->execute($input);

            return new UserResource($output)
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
        } catch (EmailAlreadyExistsException $emailAlreadyExistsException) {
            return response()->json(
                ['error' => $emailAlreadyExistsException->getMessage()],
                Response::HTTP_CONFLICT,
            );
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $output = $this->getUserUseCase->execute($id);

            return new UserResource($output)
                ->response()
                ->setStatusCode(Response::HTTP_OK);
        } catch (UserNotFoundException $userNotFoundException) {
            return response()->json(
                ['error' => $userNotFoundException->getMessage()],
                Response::HTTP_NOT_FOUND,
            );
        }
    }
}
