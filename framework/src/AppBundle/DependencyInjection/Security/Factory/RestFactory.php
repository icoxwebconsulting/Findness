<?php

namespace AppBundle\DependencyInjection\Security\Factory;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class RestFactory
 *
 * @package AppBundle\DependencyInjection\Security\Factory
 */
class RestFactory implements SecurityFactoryInterface
{
    /**
     * Inject the REST authenticator
     *
     * @param ContainerBuilder $container
     * @param $id
     * @param $config
     * @param $userProvider
     * @param $defaultEntryPoint
     * @return array
     */
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = 'security.authentication.provider.rest.' . $id;
        $container
            ->setDefinition($providerId, new DefinitionDecorator('findness.rest.security.authentication.provider'))
            ->replaceArgument(0, new Reference($userProvider));

        $listenerId = 'security.authentication.listener.rest.' . $id;
        $container->setDefinition($listenerId, new DefinitionDecorator('findness.rest.security.authentication.listener'));

        return array($providerId, $listenerId, $defaultEntryPoint);
    }

    /**
     * @inheritdoc
     */
    public function getPosition()
    {
        return 'pre_auth';
    }

    /**
     * @inheritdoc
     */
    public function getKey()
    {
        return 'rest';
    }

    /**
     * @inheritdoc
     */
    public function addConfiguration(NodeDefinition $node)
    {
    }
}