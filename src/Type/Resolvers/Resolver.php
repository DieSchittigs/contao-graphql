<?php

namespace DieSchittigs\ContaoGraphQLBundle\Type\Resolvers;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use Contao\DcaExtractor;
use Contao\Controller;
use Contao\System;
use DieSchittigs\ContaoGraphQLBundle\Type\DatabaseObjectType;

class Resolver
{
    /**
     * @var string
     */
    protected $table;

    /**
     * @var string
     */
    protected $singularName;

    /**
     * @var string
     */
    protected $pluralName;

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    /**
     * Resolve the table and return the corresponding DatabaseObjectType
     * 
     * @return DatabaseObjectType
     */
    public function resolve(): DatabaseObjectType
    {
        $config = $this->generateConfig($this->table);
        $singular = new ObjectType($config);

        return new DatabaseObjectType($singular);
    }

    public function setSingularName(string $name): Resolver
    {
        $this->singularName = $name;
        return $this;
    }

    public function setPluralName(string $name): Resolver
    {
        $this->pluralName = $name;
        return $this;
    }

    /**
     * Generates an object type configuration for the specified table
     * 
     * @param string $table The table name of the object to generate a configuration for
     * @return array
     */
    protected function generateConfig(string $table): array
    {
        System::loadLanguageFile($table, 'de');
        Controller::loadDataContainer($table);
        $dcaFields = $GLOBALS['TL_DCA'][$table]['fields'];

        $extracted = DcaExtractor::getInstance($table);

        $fields = $args = [];
        foreach ($extracted->getFields() as $name => $typedef) {
            if ($name === 'id')
                $type = $args['id'] = Type::id();
            else if ($name === 'pid')
                $type = $args['pid'] = Type::id();
                
            $fields[$name] = [
                'type' => self::determineColumnType($name, $typedef),
                'resolve' => function () { return 'Foo'; },
                'description' => $dcaFields[$name]['label'] ? $dcaFields[$name]['label'][1] : null
            ];
        }

        // Currently singularName is required for testing purposes.
        // Hardcoding will be removed later.
        $data = [
            'name' => $this->singularName,
            'fields' => $fields
        ];

        foreach ($extracted->getUniqueFields() as $unique) {
            $args[$unique] = Type::string();
        }
        
        if (count($args)) {
            $data['args'] = $args;
        }

        return $data;
    }

    /**
     * Determines a column's object type
     * 
     * @param string $name      The name of the column
     * @param string $typedef   The SQL column type definition
     * @return Type 
     */
    protected static function determineColumnType(string $name, string $typedef): Type
    {
        $type = Type::string();

        if ($name === 'id')
            $type = $args['id'] = Type::id();
        else if ($name === 'pid')
            $type = $args['pid'] = Type::id();
        else if (strpos($typedef, 'int') === 0) $type = Type::int();
        else if (strpos($typedef, 'char(1)') === 0) $type = Type::boolean();

        return $type;
    }
}
