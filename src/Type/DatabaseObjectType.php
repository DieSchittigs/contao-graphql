<?php

namespace DieSchittigs\ContaoGraphQLBundle\Type;

use GraphQL\Type\Definition\ObjectType;

class DatabaseObjectType
{
    /**
     * @var ObjectType
     */
    protected $singular;

    /**
     * @var ObjectType
     */
    protected $plural;

    public function __construct(ObjectType $singular = null, ObjectType $plural = null)
    {
        $this->singular = $singular;
        $this->plural = $plural;
    }

    public function singular(): ?ObjectType
    {
        return $this->singular;
    }

    public function plural(): ?ObjectType
    {
        return $this->plural;
    }

    public function arguments(): array
    {
        return [];
    }
}
