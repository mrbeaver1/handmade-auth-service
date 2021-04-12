<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ApiValidationException extends BadRequestHttpException
{
    /**
     * @var array | string[]
     */
    private array $errors;

    /**
     * @param array            $errors
     * @param string           $message
     * @param int              $code
     * @param Exception | null $previous
     */
    public function __construct(
        array $errors,
        string $message = '',
        int $code = 0,
        Exception $previous = null
    ) {
        $message = empty($message) ? json_encode($errors) : $message;

        parent::__construct($message, $previous, $code);

        $this->errors = $errors;
    }

    /**
     * @return array | string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
