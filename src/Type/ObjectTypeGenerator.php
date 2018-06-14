<?php

namespace DieSchittigs\ContaoGraphQLBundle\Type;

use DieSchittigs\ContaoGraphQLBundle\Type\Resolvers\ContentElement;

class ObjectTypeGenerator
{
    public static function supportedTypes()
    {
        return [
            ContentElement::class => ['contentElement', 'contentElements'],
        ];
    }

    public static function create($type): DatabaseObjectType
    {
        $types = self::supportedTypes();
        
        if (!in_array($type, array_keys($types))) {
            throw new \BadMethodCallException();
        }

        // Stub
        return new DatabaseObjectType(new $type);
    }
}
