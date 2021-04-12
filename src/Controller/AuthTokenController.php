<?php


namespace App\Controller;


use App\DTO\CreateTokenData;
use App\VO\Token;
use DateTimeImmutable;
use JOSE_Exception_InvalidFormat;
use JOSE_JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AuthTokenController extends AbstractController
{
    /**
     * @Route("/encode", methods={"POST"})
     *
     * @param CreateTokenData $createTokenData
     *
     * @return JsonResponse
     */
    public function encode(CreateTokenData $createTokenData): JsonResponse
    {
        $createAuthTokenTime = new DateTimeImmutable();
        $exprTokenTime = $createAuthTokenTime->modify('+ 24 hour')->getTimestamp();

        $jwt = new JOSE_JWT([
            'user_id' => $createTokenData->getUserId(),
            'email' => $createTokenData->getEmail(),
            'user_role' => $createTokenData->getUserRole(),
            'create_token_time' => $createAuthTokenTime,
            'expr' => $exprTokenTime,
        ]);

        return new JsonResponse([
            'token' => $jwt->toString(),
            'expr' => $exprTokenTime,
        ], JsonResponse::HTTP_CREATED);
    }

    /**
     * @Route("/decode", methods={"GET"})
     *
     * @param Token $token
     *
     * @return JsonResponse
     */
    public function decode(Token $token): JsonResponse
    {
        try {
            $jwt = JOSE_JWT::decode($token->getValue());
        } catch (JOSE_Exception_InvalidFormat $exception) {
            return new JsonResponse('Переданная строк не является токеном', JsonResponse::HTTP_BAD_REQUEST);
        }

        $tokenBody = $jwt->claims;

        if (empty($tokenBody['expr']) || (new DateTimeImmutable())->getTimestamp() >= $tokenBody['expr']) {
            return new JsonResponse('Срок действия токена истёк', JsonResponse::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse($tokenBody, JsonResponse::HTTP_OK);
    }
}
