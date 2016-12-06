<?php

namespace Frieserlabs\Bundle\QABundle\Tool;

interface ToolInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param $files
     * @param $callback
     */
    public function run($files, $callback);

    /**
     * @return boolean
     */
    public function isCritical();

    /**
     * @param boolean $critical
     *
     * @return $this
     */
    public function setCritical($critical);
}