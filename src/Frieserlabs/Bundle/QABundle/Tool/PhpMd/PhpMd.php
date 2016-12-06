<?php
namespace Frieserlabs\Bundle\QABundle\Tool\PhpMd;

use Symfony\Component\Process\ProcessBuilder;
use Frieserlabs\Bundle\QABundle\Tool\Tool;

class PhpMd extends Tool
{
    const PHPMD_TOOL_NAME = 'phpmd';
    const PHP_FILES_IN_SRC = '/^src\/(.*)(\.php)$/';
    const PHPMD_RULESETS = 'controversial,cleancode,codesize,design,naming,unusedcode';
    const PHPMD_OUTPUT_FORMAT = 'text';

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return self::PHPMD_TOOL_NAME;
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
                ['php', 'phpmd', $this->getProjectRootPath() . $file, self::PHPMD_OUTPUT_FORMAT, self::PHPMD_RULESETS]
            );
            $processBuilder->setWorkingDirectory($this->getToolsBinPath());
            $process = $processBuilder->getProcess();

            $process->mustRun($callback);
        }
    }
}