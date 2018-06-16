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

    public function getFields(): array
    {
        $list = [];

        if ($this->singular) {
            $list[$this->singular->name] = $this->singular;
        }

        if ($this->plural) {
            $list[$this->plural->name] = $this->plural;
        }

        return $list;
    }

    public function arguments(): array
    {
        return [];
    }
}
