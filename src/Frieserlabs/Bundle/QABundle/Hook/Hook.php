<?php
namespace Frieserlabs\Bundle\QABundle\Hook;

use Frieserlabs\Bundle\QABundle\Tool\ToolInterface;

abstract class Hook implements HookInterface
{
    /** @var  array */
    protected $tools;

    /**
     * PreCommit constructor.
     */
    public function __construct()
    {
        $this->tools = array();
    }

    /**
     * @inheritdoc
     */
    public function getTools()
    {
        return $this->tools;
    }

    /**
     * @inheritdoc
     */
    public function addTool(ToolInterface $tool)
    {
        $this->tools[$tool->getName()] = $tool;

        return $this;
    }
}