<?php

namespace DieSchittigs\ContaoGraphQLBundle\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class DatabaseObjectType
{
    /**
     * @var ObjectType
     */
    protected $type;

    /**
     * @var array
     */
    protected $config;

    /**
     * @param ObjectType $type
     * @param array $config
     */
    public function __construct(ObjectType $type, array $config)
    {
        $this->type = $type;
        $this->config = $config;
    }

    /**
     * Returns a field list ready for consumption in a query type
     * 
     * @return array
     */
    public function getFields(): array
    {
        $list = [];

        if (isset($this->config['singular'])) {
            $list[$this->config['singular']] = [
                'type' => $this->type,
                'args' => $this->singularArguments(),
                'resolve' => function () {
                    return ['id' => 30];
                }
            ];
        }

        if (isset($this->config['plural'])) {
            $list[$this->config['plural']] = [
                'type' => Type::listOf($this->type),
                'args' => $this->pluralArguments()
            ];
        }

        return $list;
    }

    /**
     * Returns a list of arguments for the singular form of the type
     * 
     * @return array
     */
    public function singularArguments(): array
    {
        return $this->type->config['args'];
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
