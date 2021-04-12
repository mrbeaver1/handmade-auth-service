<?php

namespace App\Validation;

use Symfony\Component\Validator\Constraints as Assert;

class CreateTokenDataValidator extends AbstractValidator
{
    /**
     * @return array
     */
    protected function getConstraints(): array
    {
        return [
            'user_id' => $this->getIdRules(),
            'email' => $this->getEmailRules(),
            'user_role' => $this->getUserRoleRules(),
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
    private function getEmailRules(): array
    {
        return [
            new Assert\Email([
                'message' => 'Недопустимое значение e-mail',
            ]),
        ];
    }

    /**
     * @return array
     */
    private function getUserRoleRules(): array
    {
        return[
            $this->getNotBlank(),
            new Assert\Type([
                'type' => 'string',
                'message' => 'Значение должно быть строкой',
            ])
        ];
    }
}
