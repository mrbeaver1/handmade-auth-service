<?php

namespace App\ArgumentResolver;

use App\DTO\CreateTokenData;
use App\Exception\ApiValidationException;
use App\Validation\CreateTokenDataValidator;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class CreateTokenDataResolver implements ArgumentValueResolverInterface
{
    /**
     * @var CreateTokenDataValidator
     */
    private CreateTokenDataValidator $validator;

    /**
     * @param CreateTokenDataValidator $validator
     */
    public function __construct(CreateTokenDataValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param Request          $request
     * @param ArgumentMetadata $argument
     *
     * @return bool
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return CreateTokenData::class === $argument->getType();
    }

    /**
     * @param Request          $request
     * @param ArgumentMetadata $argument
     *
     * @return Generator
     */
    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        $params = json_decode($request->getContent(), true);

        $userId = $params['user_id'] ?? null;
        $email = $params['email'] ?? null;
        $role = $params['user_role'] ?? null;

        $errors = $this->validator->validate([
            'user_id' => $userId,
            'email' => $email,
            'user_role' => $role,
        ]);

        if (!empty($errors)) {
            throw new ApiValidationException($errors);
        }

        yield new CreateTokenData(
            $userId,
            $email,
            $role
        );
    }
}
