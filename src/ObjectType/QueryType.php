<?php

namespace DieSchittigs\ContaoGraphQLBundle\ObjectType;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\NonNull;
use DieSchittigs\ContaoGraphQLBundle\ObjectType\ObjectTypeFactory;

class QueryType extends ObjectType
{
    public function __construct()
    {
        $types = [];
        foreach (ObjectTypeFactory::supportedTypes() as $type => $_) {
            $types[] = ObjectTypeFactory::create($type);
        }

        $config = [
            'name' => 'Query',
            'fields' => ['type' => Type::string()],
            'resolveField' => function($val, $args, $context, ResolveInfo $info) {
                return $this->{$info->fieldName}($val, $args, $context, $info);
            }
        ];

        parent::__construct($config);
    }

    public function article($rootValue, $args)
    {
        return ['title' => 'foo', 'content' => 'bar'];
    }

    public function contentElement($rootValue, $args)
    {
        return ['title' => 'foo', 'content' => 'bar'];
    }

    public function hello()
    {
        return 'Your graphql-php endpoint is ready! Use GraphiQL to browse API';
    }
}
