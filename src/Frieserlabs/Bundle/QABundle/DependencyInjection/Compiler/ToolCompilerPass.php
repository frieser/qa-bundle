<?php
namespace Frieserlabs\Bundle\QABundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ToolCompilerPass implements CompilerPassInterface
{
    const FRIESERLABS_QA_TOOL_SCANNER_SERVICE_ID = 'frieserlabs.qa_tool.quality_tester';
    const FRIESERLABS_QA_TOOL_TOOL_TAG_NAME = 'frieserlabs.qa_tool.tool';

    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(self::FRIESERLABS_QA_TOOL_SCANNER_SERVICE_ID)) {
            return;
        }
        $definition = $container->getDefinition(
            self::FRIESERLABS_QA_TOOL_SCANNER_SERVICE_ID
        );
        $taggedServices = $container->findTaggedServiceIds(
            self::FRIESERLABS_QA_TOOL_TOOL_TAG_NAME
        );
        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall(
                'addTool',
                array(new Reference($id))
            );
        }
    }
}