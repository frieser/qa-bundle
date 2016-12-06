<?php
namespace Frieserlabs\Bundle\QABundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Config\FileLocator;

class FrieserlabsQAExtension extends Extension
{
    /**
     * @inheritdoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('tools.yml');
        $loader->load('hooks.yml');

        foreach ($config as $hookName => $hookConfig) {
            if ($hookConfig['enabled']) {
                $this->processHooksConfiguration($container, $hookName, $hookConfig);
            }
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param                  $hookName
     * @param                  $hookConfig
     */
    private function processHooksConfiguration(ContainerBuilder $container, $hookName, $hookConfig)
    {
        $hookDefinition = $container->getDefinition(
            sprintf('frieserlabs.qa_tool.hook.%s', $hookName)
        );
        if (array_key_exists('tools', $hookConfig)) {
            $this->processHookConfiguration($container, $hookConfig, $hookDefinition);
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param                  $hookConfig
     * @param                  $hookDefinition
     */
    private function processHookConfiguration(ContainerBuilder $container, $hookConfig, Definition $hookDefinition)
    {
        foreach ($hookConfig['tools'] as $toolName => $toolConfig) {
            $container->setParameter(
                sprintf('frieserlabs.qa_tool.tool.%s.critical', $toolName),
                $toolConfig['critical']
            );
            $toolServiceId = sprintf('frieserlabs.qa_tool.tool.%s', $toolName);
            $toolDefinition = $container->getDefinition($toolServiceId);

            $toolDefinition->addMethodCall(
                'setCritical',
                array($toolConfig['critical'])
            );
            $hookDefinition->addMethodCall(
                'addTool',
                array(new Reference($toolServiceId))
            );
        }
    }
}