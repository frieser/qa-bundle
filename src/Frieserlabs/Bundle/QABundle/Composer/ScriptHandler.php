<?php
namespace Frieserlabs\Bundle\QABundle\Composer;

use Composer\Script\Event;

class ScriptHandler
{
    const FRIESERLABS_QA_BUNDLE_HOOKS_PATH = '/frieserlabs/qa-bundle/hooks';
    const GIT_HOOKS_PATH = '/.git/hooks';

    /**
     * Enable the git hooks in the project through composer
     *
     * @param Event $event
     */
    public static function enableGitHooks(Event $event)
    {
        $config = $event->getComposer()
                        ->getConfig();
        $vendorDir = $config->get('vendor-dir');
        $hookDir = $vendorDir . self::FRIESERLABS_QA_BUNDLE_HOOKS_PATH;
        $gitHookDir = getcwd() . self::GIT_HOOKS_PATH;

        if(!file_exists($gitHookDir)){
            $event->getIO()->writeError('<error>No default git hooks directory found. Initialize a git repository in this project to use the QA Bundle</error>');

            return;
        }

        if (!is_link($gitHookDir)) {
            if (PHP_OS === 'Windows') {
                exec("rd /s /q {$gitHookDir}");
            } else {
                exec("rm -rf {$gitHookDir}");
            }
        } else {
            unlink($gitHookDir);
        }
        symlink($hookDir, $gitHookDir);
    }
}