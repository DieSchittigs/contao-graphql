<?php

namespace DieSchittigs\ContaoGraphQLBundle\Type;

use DieSchittigs\ContaoGraphQLBundle\Type\Resolvers\ContentElement;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ObjectTypeGenerator
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var DatabaseObjectType[]
     */
    protected $resolved = [];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function supportedTypes(): array
    {
        // var_dump($this->container->getParameter('contao_graphql.types'));
        return [
            'tl_content' => ['contentElement', 'contentElements'],
        ];
    }

    public function create(string $type): DatabaseObjectType
    {
        $types = self::supportedTypes();
        
        if (!in_array($type, array_keys($types))) {
            throw new \BadMethodCallException();
        }

        $servicePrefix = 'contao.graphql.resolver.';
        try {
            $resolver = $this->container->get($servicePrefix . $type);
        } catch (\Exception $e) {
            $resolver = $this->container->get($resolver . 'default');
        }

        // var_dump($resolver);

        // Stub
        return new DatabaseObjectType(new $type);
    }
}
