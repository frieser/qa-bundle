<?php

namespace Frieserlabs\Bundle\QABundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\ScalarNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @inheritdoc
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('frieserlabs_qa');

        $rootNode->append($this->addPreCommitNode())
                 ->append($this->addCommitMsgNode())
                 ->append($this->addPrePush());

        return $treeBuilder;
    }

    /**
     * @return ArrayNodeDefinition|\Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    private function addPreCommitNode()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('pre_commit');

        $rootNode
            ->canBeEnabled()
            ->children()
                ->append($this->addToolsNode())
            ->end();

        return $rootNode;
    }

    /**
     * @return ArrayNodeDefinition|\Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    private function addCommitMsgNode()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('commit_msg');

        $rootNode
            ->canBeEnabled()
            ->children()
                ->append($this->addToolsNode())
            ->end();

        return $rootNode;
    }

    /**
     * @return ArrayNodeDefinition|\Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    private function addPrePush()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('pre_push');

        $rootNode
            ->canBeEnabled()
            ->children()
                ->append($this->addToolsNode())
            ->end();

        return $rootNode;
    }

    /**
     * @return ArrayNodeDefinition
     */
    private function addToolsNode(){
        $nodeBuilder = new ArrayNodeDefinition('tools');

        $nodeBuilder
            ->useAttributeAsKey('name')
              ->prototype('array')
              ->canBeEnabled()
                  ->children()
                    ->append($this->addCriticalNode())
                    ->append($this->addConfigurationNode())
                  ->end()
              ->end()
            ->end();

        return $nodeBuilder;
    }

    /**
     * @return ScalarNodeDefinition
     */
    private function addCriticalNode()
    {
        $nodeBuilder = new ScalarNodeDefinition('critical');
        $nodeBuilder->defaultTrue();

        return $nodeBuilder;
    }

    /**
     * @return ArrayNodeDefinition
     */
    private function addConfigurationNode()
    {
        $nodeBuilder = new ArrayNodeDefinition('extra_config');

        $nodeBuilder
            ->useAttributeAsKey('name')
                ->prototype('array')
                ->canBeEnabled()
                ->end()
            ->end();

        return $nodeBuilder;
    }
}