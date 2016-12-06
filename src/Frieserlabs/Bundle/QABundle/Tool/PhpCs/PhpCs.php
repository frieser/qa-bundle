<?php
namespace Frieserlabs\Bundle\QABundle\Tool\PhpCs;

use Symfony\Component\Process\ProcessBuilder;
use Frieserlabs\Bundle\QABundle\Tool\Tool;

class PhpCs extends Tool
{
    const PHP_CS_TOOL_NAME = 'phpcs';
    const PHP_FILES_IN_SRC = '/^src\/(.*)(\.php)$/';

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return self::PHP_CS_TOOL_NAME;
    }

    /**
     * @inheritdoc
     */
    public function run($files, $callback = null)
    {
        $needle = self::PHP_FILES_IN_SRC;

        foreach ($files as $file) {
            if (!preg_match($needle, $file)) {
                continue;
            }
            $processBuilder = new ProcessBuilder(
                array('php', 'phpcs', '--standard=PSR2', $this->getProjectRootPath() . $file)
            );
            $processBuilder->setWorkingDirectory($this->getToolsBinPath());
            $phpCs = $processBuilder->getProcess();

            $phpCs->mustRun($callback);
        }
    }
}