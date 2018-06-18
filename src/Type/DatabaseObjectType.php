<?php

namespace DieSchittigs\ContaoGraphQLBundle\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

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

    /**
     * @param ObjectType $singular
     * @param ObjectType $plural
     */
    public function __construct(ObjectType $type = null)
    {
        $this->type = $type;
    }

    /**
     * Returns a field list ready for consumption in a query type
     * 
     * @return array
     */
    public function getFields(): array
    {
        $list = [];

        $list[$this->type->name] = [
            'type' => $this->type,
            'args' => $this->singularArguments(),
            'resolve' => function () {
                return ['id' => 30];
            }
        ];

        $list[$this->type->name . 's'] = [
            'type' => Type::listOf($this->type),
            'args' => $this->pluralArguments()
        ];

        return $list;
    }

    /**
     * Returns a list of arguments for the singular form of the type
     * 
     * @return array
     */
    public function singularArguments(): array
    {
        return [];
    }

    /**
     * Returns a list of arguments for the plural form of the type
     * 
     * @return array
     */
    public function pluralArguments(): array
    {
        return [];
    }
}
