<?php

namespace DieSchittigs\ContaoGraphQLBundle\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Contao\Controller;

class ContentElementType extends ContaoObjectType
{
    public function __construct()
    {
        // var_dump(self::generateConfig('tl_content'));die();
        $config = ['fields' => [
            'id' => Type::id(),
            'title' => Type::string()
        ]];
        
        Controller::loadDataContainer('tl_content', true);

        $fields = $GLOBALS['TL_DCA']['tl_content']['fields'];
        foreach ($fields as $fieldKey => $field) {
            $config['fields'][$fieldKey] = Type::string();
        }

        ObjectType::__construct($config);
    }
}
