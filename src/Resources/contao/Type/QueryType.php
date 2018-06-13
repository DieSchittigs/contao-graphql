<?php

namespace DieSchittigs\ContaoGraphQLBundle\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\NonNull;
use DieSchittigs\ContaoGraphQLBundle\Types;

class QueryType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Query',
            'fields' => [
                'contentElement' => [
                    'type' => Types::contentElement(),
                    'description' => 'Returns Content Element by id',
                    'args' => [
                        'id' => new NonNull(Types::id())
                    ]
                ],
                'hello' => Types::string()
            ],
            'resolveField' => function($val, $args, $context, ResolveInfo $info) {
                return $this->{$info->fieldName}($val, $args, $context, $info);
            }
        ];
        
        parent::__construct($config);
    }

    public function contentElement($rootValue, $args)
    {
        return ['title' => 'dsfsf', 'content' => 'fdsfs'];
    }

    public function hello()
    {
        return 'Your graphql-php endpoint is ready! Use GraphiQL to browse API';
    }
}
