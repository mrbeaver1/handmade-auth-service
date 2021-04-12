<?php

namespace App\Validation;

use Symfony\Component\Validator\Constraints as Assert;

class TokenValueValidator extends AbstractValidator
{
    /**
     * @return array
     */
    protected function getConstraints(): array
    {
        return [
            'token' => $this->getTokenRules(),
        ];
    }

    /**
     * @return array
     */
    protected function getOptionalFields(): array
    {
        return [];
    }

    /**
     * @return array
     */
    private function getTokenRules(): array
    {
        return [
            $this->getNotBlank(),
            new Assert\Type([
                'type' => 'string',
                'message' => 'Значение должно быть строкой',
            ]),
        ];
    }
}
