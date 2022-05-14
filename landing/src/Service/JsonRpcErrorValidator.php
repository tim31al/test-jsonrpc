<?php

/*
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 */

namespace App\Service;

use App\Service\Exception\JsonRpcErrorException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class JsonRpcErrorValidator
{
    private ValidatorInterface $validator;
    private Collection $constraint;

    public function __construct()
    {
        $this->validator = Validation::createValidator();
        $this->constraint = new Assert\Collection([
            'jsonrpc' => new Assert\Regex('/^2.0$/'),
            'id' => new Assert\Optional(),
            'error' => new Assert\Collection([
                'message' => new Assert\NotBlank(),
                'code' => new Assert\Type('integer'),
                'data' => new Assert\Optional(),
            ]),
        ]);
    }

    /**
     * @throws \App\Service\Exception\JsonRpcErrorException
     */
    public function validate(?array $data): void
    {
        if (!$data) {
            return;
        }

        $violations = $this->validator->validate($data, $this->constraint);

        if (0 === \count($violations)) {
            $message = sprintf(
                'code: %d, message: %s',
                $data['error']['code'],
                $data['error']['message']
           );

            throw new JsonRpcErrorException($message);
        }
    }
}
