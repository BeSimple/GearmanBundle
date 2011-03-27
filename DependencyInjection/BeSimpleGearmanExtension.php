<?php

namespace BeSimple\GearmanBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

/**
 * @author Francis Besset <francis.besset@gmail.com>
 */
class BeSimpleGearmanExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor     = new Processor();
        $configuration = new Configuration();

        $config = $processor->process($configuration->getConfigTree(), $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('servers.xml');

        $container->setParameter('besimple.gearman.timeout', $config['timeout']);

        $this->remapServers($config, $container);
    }

    protected function remapServers(array $config, ContainerBuilder $container)
    {
        $workerServers = array();
        $clientServers = array();

        foreach ($config['servers'] as $server) {
            $clientServers[] = $server['host'].($server['port'] ? ':'.$server['port'] : '');

            if (true === $server['active']['worker']) {
                $workerServers[] = $server['host'].($server['port'] ? ':'.$server['port'] : '');
            }
        }

        $container->setParameter('besimple.gearman.servers.client', implode(',', $clientServers));
        $container->setParameter('besimple.gearman.servers.worker', implode(',', $workerServers));
    }
}