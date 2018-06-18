<?php

namespace DieSchittigs\ContaoGraphQLBundle\Type;

use DieSchittigs\ContaoGraphQLBundle\Type\Resolvers\ContentElement;
use Symfony\Component\DependencyInjection\ContainerInterface;
use DieSchittigs\ContaoGraphQLBundle\Type\Resolvers\Resolver;

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

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Returns a list of all supported types
     * 
     * @return array
     */
    public function supportedTypes(): array
    {
        return $this->container->getParameter('contao_graphql.types');
    }

    /**
     * Returns configuration options for a given type
     * 
     * @param string $type The type to generate the configuration for
     * @return array
     */
    protected function configuration(string $type): array
    {
        // This works for now, but is definitely not pretty.
        // Plus, the singular and plural name are only relevant for the DatabaseObjectType.
        // Really we don't care at the ObjectTypeGenerator level.

        // Re-evaluate and refactor this to something nicer.

        $singularType = 'contao_graphql.type.' . $type . '.singular';
        if ($this->container->hasParameter($singularType)) {
            $singular = $this->container->getParameter($singularType);
        } else {
            $singular = ucfirst(str_replace('tl_', '', $type));
        }

        $pluralType = 'contao_graphql.type.' . $type . '.plural';
        if ($this->container->hasParameter($pluralType)) {
            $plural = $this->container->getParameter($pluralType);
        } else {
            $plural = null;
        }

        $resolverType = 'contao_graphql.type.' . $type . '.resolver';
        if ($this->container->hasParameter($resolverType)) {
            $resolver = $this->container->getParameter($resolverType);
        } else {
            $resolver = Resolver::class;
        }

        return [
            'singular' => $singular,
            'plural' => $plural,
            'resolver' => $resolver
        ];
    }

    /**
     * Generates or reuses an existing DatabaseObjectType for the given type name
     * 
     * @param string $type Type name of the object type
     * @return DatabaseObjectType
     */
    public function create(string $type): DatabaseObjectType
    {
        if (isset($this->resolved[$type])) {
            return $this->resolved[$type];
        }

        if (!in_array($type, $this->supportedTypes())) {
            throw new \BadMethodCallException();
        }

        $configuration = $this->configuration($type);

        $resolver = new $configuration['resolver']($type);
        $resolver->setSingularName($configuration['singular']);

        return $this->resolved[$type] = $resolver->resolve();
    }
}
