<?php
namespace Frieserlabs\Bundle\QABundle\Tool\PhpCsFixer;

use Symfony\Component\Process\ProcessBuilder;
use Frieserlabs\Bundle\QABundle\Tool\Tool;

class PhpCsFixer extends Tool
{
    const PHP_CS_FIXER_TOOL_NAME = 'phpcs_fixer';
    const PHP_FILES_IN_SRC = '/^src\/(.*)(\.php)$/';
    const PHP_FILES_IN_CLASSES = '/^classes\/(.*)(\.php)$/';

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return self::PHP_CS_FIXER_TOOL_NAME;
    }

    public function run($files, $callback = null)
    {
        foreach ($files as $file) {
            $classesFile = preg_match(self::PHP_FILES_IN_CLASSES, $file);
            $srcFile = preg_match(self::PHP_FILES_IN_SRC, $file);

            if (!$classesFile && !$srcFile) {
                continue;
            }
            $fixers = '-psr0';

            if ($classesFile) {
                $fixers = 'eof_ending,indentation,linefeed,lowercase_keywords,trailing_spaces,short_tag,php_closing_tag,extra_empty_lines,elseif,function_declaration';
            }
            $processBuilder = new ProcessBuilder(
                array('php', 'php-cs-fixer', '--dry-run', '--verbose', 'fix', $this->getProjectRootPath() . $file, '--fixers=' . $fixers)
            );
            $processBuilder->setWorkingDirectory($this->getToolsBinPath());
            $phpCsFixer = $processBuilder->getProcess();

            $phpCsFixer->mustRun($callback);
        }
    }
}