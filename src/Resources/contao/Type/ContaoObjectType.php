<?php

namespace DieSchittigs\ContaoGraphQLBundle\Type;

use GraphQL\Type\Definition\ObjectType;
use DieSchittigs\ContaoGraphQLBundle\Types;
use Contao\DcaExtractor;
use Contao\Controller;
use Contao\System;

class ContaoObjectType extends ObjectType
{
    public function __construct($config)
    {
        $config = self::generateConfig($config);
        parent::__construct($config);
    }

    protected static function generateConfig($table)
    {
        System::loadLanguageFile($table, 'de'); // TODO: Adjust language!
        Controller::loadDataContainer($table);
        $dcaFields = $GLOBALS['TL_DCA'][$table]['fields'];

        $extracted = DcaExtractor::getInstance($table);

        $fields = [];
        $args = [];
        foreach ($extracted->getFields() as $name => $typedef) {
            $type = Types::string();
            if($name === 'id')
                $type = $args['id'] = Types::id();
            else if($name === 'pid')
                $type = $args['pid'] = Types::id();
            else if (strpos($typedef, 'int') === 0) $type = Types::int();
            else if (strpos($typedef, 'char(1)') === 0) $type = Types::boolean();
            $fields[$name] = [
                'type' => $type,
                'resolve' => function () { return 'Foo'; },
                'description' => $dcaFields[$name]['label'] ? $dcaFields[$name]['label'][1] : null
            ];
        }

        $data = [
            'name' => ucfirst(substr($table, 3)),
            'fields' => $fields
        ];

        foreach ($extracted->getUniqueFields() as $unique) {
            $args[$unique] = Types::string();
        }
        
        if (count($args)) {
            $data['args'] = $args;
        }
        return $data;
    }
}
