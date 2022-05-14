<?php

/*
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 */

namespace App\Service;

use App\Service\Exception\JsonRpcErrorException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class JsonRpcGetClicksValidator
{
    public const ERROR_MESSAGE = 'Ошибка получения данных';

    private ValidatorInterface $validator;
    private Collection $constraint;
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->validator = Validation::createValidator();
        $this->constraint = new Assert\Collection([
            'jsonrpc' => new Assert\Regex('/^2.0$/'),
            'id' => new Assert\GreaterThan(0),
            'result' => new Assert\Collection([
                'clicks' => [
                    new Assert\Type('array'),
                    new Assert\All([
                        new Assert\Collection([
                            'fields' => [
                                'id' => new Assert\Optional(),
                                'url' => new Length(['min' => 1, 'max' => 255]),
                                'counter' => new Assert\GreaterThan(0),
                                'lastVisit' => new Assert\DateTime(),
                            ],
                        ])
                    ])
                ],
                'countAll' => new Assert\GreaterThanOrEqual(0),
            ]),
        ]);
    }

    /**
     * @throws \App\Service\Exception\JsonRpcErrorException
     */
    public function validate(array $data): void
    {
        $violations = $this->validator->validate($data, $this->constraint);

        if (0 !== \count($violations)) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[] = $violation->getPropertyPath() . ':' . $violation->getMessage();
            }

            $message = implode(', ', $errors);
            $this->logger->error($message);
            throw new JsonRpcErrorException(static::ERROR_MESSAGE);
//            throw new JsonRpcErrorException($message);
        }
    }
}
