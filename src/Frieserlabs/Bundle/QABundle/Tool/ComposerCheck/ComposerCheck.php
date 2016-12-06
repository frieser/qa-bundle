<?php
namespace Frieserlabs\Bundle\QABundle\Tool\ComposerCheck;

use Symfony\Component\Process\ProcessBuilder;
use Frieserlabs\Bundle\QABundle\Exception\NonCriticalToolCommandException;
use Frieserlabs\Bundle\QABundle\Tool\Tool;

class ComposerCheck extends Tool
{
    const COMPOSER_CHECK_TOOL_NAME = 'composer_check';
    const ECHO_BIN = 'echo';
    const ALL_COMPOSER_CHECKS_OK = 'All composer checks OK!';

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return self::COMPOSER_CHECK_TOOL_NAME;
    }

    /**
     * @inheritdoc
     */
    public function run($files, $callback = null)
    {
        $composerJsonDetected = false;
        $composerLockDetected = false;

        foreach ($files as $file) {
            if ($file === $this->getProjectRootPath() . 'composer.json') {
                $composerJsonDetected = true;
            }
            if ($file === $this->getProjectRootPath() . 'composer.lock') {
                $composerLockDetected = true;
            }
        }
        if ($composerJsonDetected && !$composerLockDetected) {
            throw new \RuntimeException('composer.lock must be committed if composer.json is modified!');
        }
        if ($composerLockDetected) {
            throw new NonCriticalToolCommandException('You are going to update your dependencies');
        }

        ProcessBuilder::create(
            array(
                self::ECHO_BIN,
                self::ALL_COMPOSER_CHECKS_OK,
            )
        )
        ->getProcess()
        ->run($callback);
    }
}