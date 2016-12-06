<?php
namespace Frieserlabs\Bundle\QABundle\Tool\PhpLint;

use Symfony\Component\Process\ProcessBuilder;
use Frieserlabs\Bundle\QABundle\Tool\Tool;

class PhpLint extends Tool
{
    const PHP_LINT_TOOL_NAME = 'phplint';

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return self::PHP_LINT_TOOL_NAME;
    }

    /**
     * @inheritdoc
     */
    public function run($files, $callback = null)
    {
        $needle = '/(\.php)|(\.inc)$/';

        foreach ($files as $file) {
            if (!preg_match($needle, $file)) {
                continue;
            }
            $processBuilder = new ProcessBuilder(array('php', '-l', $file));
            $process = $processBuilder->getProcess();

            $process->mustRun($callback);
        }
    }
}