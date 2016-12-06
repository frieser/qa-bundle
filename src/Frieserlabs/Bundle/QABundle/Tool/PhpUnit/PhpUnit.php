<?php
namespace Frieserlabs\Bundle\QABundle\Tool\PhpUnit;

use Symfony\Component\Process\ProcessBuilder;
use Frieserlabs\Bundle\QABundle\Tool\Tool;

class PhpUnit extends Tool
{
    const PHPUNIT_TOOL_NAME = 'phpunit';

    protected $phpUnitDistXmlPath;

    /**
     * PhpUnit constructor.
     *
     * @param string $toolsBinPath
     * @param string $projectRootPath
     * @param        $phpUnitDistXmlPath
     */
    public function __construct($toolsBinPath, $projectRootPath, $phpUnitDistXmlPath)
    {
        parent::__construct($toolsBinPath, $projectRootPath);
        $this->phpUnitDistXmlPath = $phpUnitDistXmlPath;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return self::PHPUNIT_TOOL_NAME;
    }

    /**
     * @return string
     */
    public function getPhpUnitDistXmlPath()
    {
        return $this->phpUnitDistXmlPath;
    }

    /**
     * @param string $phpUnitDistXmlPath
     *
     * @return PhpUnit
     */
    public function setPhpUnitDistXmlPath($phpUnitDistXmlPath)
    {
        $this->phpUnitDistXmlPath = $phpUnitDistXmlPath;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function run($files = null, $callback = null)
    {
        $processBuilder = new ProcessBuilder(
            array('php', 'phpunit', "--configuration=" . $this->getPhpUnitDistXmlPath())
        );
        $processBuilder->setWorkingDirectory($this->getToolsBinPath());
        $processBuilder->setTimeout(3600);
        $phpunit = $processBuilder->getProcess();

        $phpunit->mustRun($callback);
    }
}