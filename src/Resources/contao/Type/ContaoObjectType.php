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
        Controller::loadDataContainer($table, true);
        $dcaFields = $GLOBALS['TL_DCA'][$table]['fields'];

        $extracted = DcaExtractor::getInstance($table);

        $fields = [];
        foreach ($extracted->getFields() as $name => $typedef) {
            $type = Types::string();

            if (strpos($typedef, 'char(1)') === 0) $type = Types::boolean();
            $fields[$name] = [
                'type' => $type,
                'resolve' => function () { return 'Foo'; },
                'description' => $dcaFields[$name]['label'] ? $dcaFields[$name]['label'][1] : null
            ];
        }

        $data = [
            'type' => [
                'fields' => $fields
            ]
        ];

        $args = [];
        foreach ($extracted->getUniqueFields() as $unique) {
            $args[$unique] = [
                'name' => $unique,
                'type' => Types::string()
            ];
        }

        if (count($args)) {
            $data['args'] = $args;
        }

        return $data;
    }
}
