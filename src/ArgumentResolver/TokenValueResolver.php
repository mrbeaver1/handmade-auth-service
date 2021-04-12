<?php

namespace App\ArgumentResolver;

use App\Exception\ApiValidationException;
use App\Validation\TokenValueValidator;
use App\VO\Token;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class TokenValueResolver implements ArgumentValueResolverInterface
{
    /**
     * @var TokenValueValidator
     */
    private TokenValueValidator $validator;

    /**
     * @param TokenValueValidator $validator
     */
    public function __construct(TokenValueValidator $validator)
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
        return Token::class === $argument->getType();
    }

    /**
     * @param Request          $request
     * @param ArgumentMetadata $argument
     *
     * @return Generator
     */
    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        $token = $request->get('token');

        $errors = $this->validator->validate(['token' => $token]);

        if (!empty($errors)) {
            throw new ApiValidationException($errors);
        }

        yield new Token($token);
    }
}
