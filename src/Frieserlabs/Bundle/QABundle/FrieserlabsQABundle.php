<?php

namespace Frieserlabs\Bundle\QABundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Frieserlabs\Bundle\QABundle\DependencyInjection\Compiler\HookCompilerPass;
use Frieserlabs\Bundle\QABundle\DependencyInjection\Compiler\ToolCompilerPass;

class FrieserlabsQABundle extends Bundle
{
    /**
     * @inheritdoc
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new HookCompilerPass());
        $container->addCompilerPass(new ToolCompilerPass());
    }
}