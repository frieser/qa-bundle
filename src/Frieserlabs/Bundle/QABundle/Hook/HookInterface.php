<?php
namespace Frieserlabs\Bundle\QABundle\Hook;

use Frieserlabs\Bundle\QABundle\Tool\ToolInterface;

interface HookInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param ToolInterface $tool
     *
     * @return $this
     */
    public function addTool(ToolInterface $tool);

    /**
     * @return array
     */
    public function getTools();
}