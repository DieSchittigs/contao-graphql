<?php

namespace DieSchittigs\ContaoGraphQLBundle;

use DieSchittigs\ContaoGraphQLBundle\Type\ContentElementType;
use DieSchittigs\ContaoGraphQLBundle\Type\QueryType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\Type;

class Types
{
    /**
     * @var ObjectType[]
     */
    private static $instantiatedTypes = [];

    /**
     * Returns a mapping of supported GraphQL type names to their associated ObjectType
     * 
     * @return array
     */
    public static function typeList()
    {
        return [
            'contentElement' => ContentElementType::class,
            'articles' => 'tl_article',
        ];
    }

    /**
     * @throws \BadMethodCallException if the called function does not match any known types
     * 
     * @return ObjectType
     */
    public static function __callStatic($name, $arguments)
    {
        if (isset(self::$instantiatedTypes[$name])) {
            return self::$instantiatedTypes[$name];
        }

        $types = self::typeList();
        if (!isset($types[$name])) {
            throw new \BadMethodCallException(self::class . ' does not know any types called ' . $name);
        }

        $type = $types[$name];
        if (is_subclass_of($type, ObjectType::class)) {
            return self::$instantiatedTypes[$name] = new $type;
        }

        return self::$instantiatedTypes[$name] = new GenericObjectType($type);
    }
    
    /**
     * @return \GraphQL\Type\Definition\BooleanType
     */
    public static function boolean()
    {
        return Type::boolean();
    }

    /**
     * @return \GraphQL\Type\Definition\FloatType
     */
    public static function float()
    {
        return Type::float();
    }

    /**
     * @return \GraphQL\Type\Definition\IDType
     */
    public static function id()
    {
        return Type::id();
    }

    /**
     * @return \GraphQL\Type\Definition\IntType
     */
    public static function int()
    {
        return Type::int();
    }

    /**
     * @return \GraphQL\Type\Definition\StringType
     */
    public static function string()
    {
        return Type::string();
    }

    /**
     * @param Type $type
     * @return ListOfType
     */
    public static function listOf($type)
    {
        return new ListOfType($type);
    }

    /**
     * @param Type $type
     * @return NonNull
     */
    public static function nonNull($type)
    {
        return new NonNull($type);
    }
}
