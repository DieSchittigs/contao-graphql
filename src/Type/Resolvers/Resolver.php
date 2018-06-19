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
     * @var array
     */
    protected $configuration;

    public function __construct(string $table, array $config)
    {
        $this->table = $table;
        $this->config = $config;
    }

    /**
     * Resolve the table and return the corresponding DatabaseObjectType
     * 
     * @return DatabaseObjectType
     */
    public function generateType(): DatabaseObjectType
    {
        $config = $this->generateTypeConfig($this->table);
        $singular = new ObjectType($config);

        return new DatabaseObjectType($singular, $this->config);
    }

    /**
     * Generates an object type configuration for this object's table
     * 
     * @param string $table The table name of the object to generate a configuration for
     * @return array
     */
    protected function generateTypeConfig(): array
    {
        $table = $this->table;

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
                // 'resolve' => [$this, 'resolve'],
                'description' => $dcaFields[$name]['label'] ? $dcaFields[$name]['label'][1] : null
            ];
        }

        // Currently singularName is required for testing purposes.
        // Hardcoding will be removed later.
        $data = [
            'name' => $this->config['type'],
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
