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
        $fields = [];
        foreach (Types::typeList() as $name => $_) {
            $fields[$name] = Types::$name();
        }
        
        $config = [
            'name' => 'Query',
            'fields' => array_merge([
                'hello' => Types::string()
            ], $fields),
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
