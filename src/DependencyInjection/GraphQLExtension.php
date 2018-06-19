<?php

namespace DieSchittigs\ContaoGraphQLBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

class GraphQLExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->createConfiguration($configs);
        $container->setParameter('contao.graphql.types', array_keys($config));
        $container->setParameter('contao.graphql.config', $config);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * {@inheritDoc}
     */
    public function getAlias()
    {
        return 'graphql';
    }

    /**
     * Creates a configuration array for GraphQL types and fields
     *
     * @param array $configs A list of configuration options
     * @return array
     */
    protected function createConfiguration(array $configs)
    {
        array_unshift($configs, Yaml::parse(file_get_contents(__DIR__ . '/../Resources/config/graphql.yml')));
        $configuration = new Configuration;
        $config = $this->processConfiguration($configuration, $configs);

        foreach ($config as &$field) {
            $field['type'] = $field['type'] ?? $field['singular'];
        }

        return $config;
    }
}
