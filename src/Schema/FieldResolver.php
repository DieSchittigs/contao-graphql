<?php

namespace DieSchittigs\ContaoGraphQLBundle\Schema;

use DieSchittigs\ContaoGraphQLBundle\Type\DatabaseObjectType;

class FieldResolver
{
    protected $fields = [];

    public function addField(DatabaseObjectType $type)
    {
        return;
    }

    public function resolveField()
    {
        return [['id' => 25], ['id' => 30], ['id' => 45]];
    }
}
