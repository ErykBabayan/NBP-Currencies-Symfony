<?php

namespace App\ValueResolver;

use App\DTO\Interface\DTO;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DataValueObjectResolver implements ValueResolverInterface
{
    public function __construct(
        public ValidatorInterface $validator,
        public SerializerInterface $serializer,
    ) {
    }

    /**
     * @return iterable<Request|DTO>
     * @throws Exception
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();
        if (
            ! $argumentType
            || ! is_subclass_of($argumentType, DTO::class)
        ) {
            return [];
        }

        try {
            $dto = $this->serializer->deserialize(
                data: $request->getContent(),
                type: $argumentType,
                format: 'json',
            );
        } catch (Exception $e) {
            // TODO Add custom exception for not valid fields and for invalid json
            throw new Exception($e->getMessage());
        }

        /** @var ConstraintViolationList $errors */
        $errors = $this->validator->validate($dto);
        $errorsString = (string)$errors;
        if (count($errors)) {
            // TODO Add custom validator exception
            throw new HttpException(422, $errorsString);
        }

        return [$dto];
    }
}
