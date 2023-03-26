<?php

namespace App\DTO\Interface;

use App\Entity\AbstractEntity;

interface Resource
{
    public static function fromObject(AbstractEntity $object): self;

    /** @param array<AbstractEntity> $objects */
    /** @return Resource[] */
    public static function fromObjects(array $objects): array;
}
