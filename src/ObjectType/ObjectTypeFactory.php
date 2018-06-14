<?php

namespace DieSchittigs\ContaoGraphQLBundle\ObjectType;

use DieSchittigs\ContaoGraphQLBundle\ObjectType\Resolvers\ContentElement;


class ObjectTypeFactory
{
    public static function supportedTypes()
    {
        return [
            ContentElement::class => ['contentElement', 'contentElements'],
        ];
    }

    public static function create($type)
    {
        $types = self::supportedTypes();
        
        if (!in_array($type, array_keys($types))) {
            throw new \BadMethodCallException();
        }

        // Stub
        return new $type;
    }
}
