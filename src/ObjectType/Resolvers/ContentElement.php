<?php

namespace DieSchittigs\ContaoGraphQLBundle\ObjectType\Resolvers;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Contao\Controller;

class ContentElement extends Resolver
{
    public function __construct()
    {
        $config = ['fields' => [
            'id' => Type::id(),
            'title' => Type::string()
        ]];
        
        Controller::loadDataContainer('tl_content', true);

        $fields = $GLOBALS['TL_DCA']['tl_content']['fields'];
        foreach ($fields as $fieldKey => $field) {
            $config['fields'][$fieldKey] = Type::string();
        }
    }
}
