<?php

namespace Frieserlabs\Bundle\QABundle\Tool;

abstract class Tool implements ToolInterface
{
    /** @var  bool */
    protected $critical;

    /** @var  string */
    protected $toolsBinPath;

    /** @var  string */
    protected $projectRootPath;

    /**
     * Tool constructor.
     *
     * @param string $toolsBinPath
     * @param string $projectRootPath
     */
    public function __construct($toolsBinPath, $projectRootPath)
    {
        $this->toolsBinPath = $toolsBinPath;
        $this->projectRootPath = $projectRootPath;
    }

    /**
     * @inheritdoc
     */
    public function isCritical()
    {
        return $this->critical;
    }

    /**
     * @inheritdoc
     */
    public function setCritical($critical)
    {
        $this->critical = $critical;

        return $this;
    }

    /**
     * @return string
     */
    public function getToolsBinPath()
    {
        return $this->toolsBinPath;
    }

    /**
     * @param string $toolsBinPath
     *
     * @return Tool
     */
    public function setToolsBinPath($toolsBinPath)
    {
        $this->toolsBinPath = $toolsBinPath;

        return $this;
    }

    /**
     * @return string
     */
    public function getProjectRootPath()
    {
        return $this->projectRootPath;
    }

    /**
     * @param string $projectRootPath
     *
     * @return Tool
     */
    public function setProjectRootPath($projectRootPath)
    {
        $this->projectRootPath = $projectRootPath;

        return $this;
    }
}