<?php

namespace idoit\Module\Lfischer_commander;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class CommanderExtension extends Extension
{
    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        (new YamlFileLoader($container, new FileLocator(\isys_module_lfischer_commander::getPath())))->load('src/services.yml');
    }
}
