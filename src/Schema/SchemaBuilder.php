<?php

namespace DieSchittigs\ContaoGraphQLBundle\Schema;

use DieSchittigs\ContaoGraphQLBundle\Type\ObjectTypeGenerator;
use GraphQL\Type\Schema;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class SchemaBuilder
{
    public function __construct(ObjectTypeGenerator $generator)
    {
        $this->generator = $generator;        
    }

    /**
     * Generates and builds the query schema
     * 
     * @return Schema
     */
    public function build(): Schema
    {
        $resolver = new FieldResolver;

        $fields = [];
        foreach ($this->generator->supportedTypes() as $type) {
            $objectType = $this->generator->create($type);
            $resolver->addField($objectType);
            $fields = array_merge($fields, $objectType->getFields());
        }

        $query = new ObjectType([
            'name' => 'Query',
            'fields' => $fields,
            'resolveField' => [$resolver, 'resolveField'],
        ]);
        
        return new Schema([
            'query' => $query,
        ]);
    }

    public function article($rootValue, $args)
    {
        return ['title' => 'foo', 'content' => 'bar'];
    }

    public function Content($rootValue, $args)
    {
        return ['id' => 123, 'content' => 'bar'];
    }

    public function hello()
    {
        return 'Your graphql-php endpoint is ready! Use GraphiQL to browse API';
    }
}