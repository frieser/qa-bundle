<?php
namespace Frieserlabs\Bundle\QABundle\QualityTester;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Process\ProcessBuilder;
use Frieserlabs\Bundle\QABundle\Exception\CriticalToolException;
use Frieserlabs\Bundle\QABundle\Exception\NonCriticalToolCommandException;
use Frieserlabs\Bundle\QABundle\Hook\HookInterface;
use Frieserlabs\Bundle\QABundle\Tool\ToolInterface;

class QualityTester
{
    const FIGLET_BIN = 'figlet';
    const ECHO_BIN = 'echo';

    /** @var  array */
    protected $hooks;

    /** @var  array */
    protected $tools;

    /**
     * QualityScanner constructor.
     */
    public function __construct()
    {
        $this->hooks = array();
        $this->tools = array();
    }

    /**
     * @param HookInterface $hook
     */
    public function addHook(HookInterface $hook)
    {
        $this->hooks[$hook->getName()] = $hook;
    }

    /**
     * @param ToolInterface $tool
     */
    public function addTool(ToolInterface $tool)
    {
        $this->tools[$tool->getName()] = $tool;
    }

    /**
     * @return array
     */
    public function getHooks()
    {
        return $this->hooks;
    }

    /**
     * @return array
     */
    public function getTools()
    {
        return $this->tools;
    }

    /**
     * @param               $dispatchedHookName
     * @param callable|null $callback
     *
     * @throws \Symfony\Component\Process\Exception\LogicException
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     * @throws \Frieserlabs\Bundle\QABundle\Exception\CriticalToolException
     */
    public function test($dispatchedHookName, callable $callback = null)
    {
        /** @var array $hooks */
        $hooks = $this->getHooks();

        if (!array_key_exists($dispatchedHookName, $hooks)) {
            ProcessBuilder::create(array(
                self::ECHO_BIN,
                sprintf(
                    '<comment>You don\'t have the dispatched hook (%s) configured</comment>',
                    $dispatchedHookName
                )
            ))
          ->getProcess()
          ->run($callback);

            return;
        }
        /** @var HookInterface $dispatchedHook */
        $dispatchedHook = $hooks[$dispatchedHookName];
        $tools = $dispatchedHook->getTools();

        $this->runTools($tools, $this->extractCommittedFiles(), $callback);
    }

    /**
     * @param array    $tools
     * @param array    $files
     * @param callable $callback
     *
     * @throws \Frieserlabs\Bundle\QABundle\Exception\CriticalToolException
     * @throws \Symfony\Component\Process\Exception\LogicException
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     */
    private function runTools(array $tools, array $files, callable $callback = null)
    {
        /** @var ToolInterface $tool */
        foreach ($tools as $tool) {
            $this->runTool($tool, $files, $callback);
        }
    }

    /**
     * @param          $tool
     * @param array    $files
     * @param callable $callback
     *
     * @throws \Symfony\Component\Process\Exception\LogicException
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     * @throws \Frieserlabs\Bundle\QABundle\Exception\CriticalToolException
     */
    private function runTool(ToolInterface $tool, array $files, callable $callback)
    {
        if (!array_key_exists($tool->getName(), $this->getTools())) {
            throw new InvalidConfigurationException('You don\'t have the configured tools available');
        }
        try {
            $this->printToolName($tool, $callback);
            $tool->run($files, $callback);
        } catch (NonCriticalToolCommandException $exception) {

            ProcessBuilder::create(array(self::ECHO_BIN, '<comment>Warning: ' . $exception->getMessage() . '</comment>'))
                          ->getProcess()
                          ->run($callback);
        } catch (\RuntimeException $exception) {

            if ($tool->isCritical()) {
                ProcessBuilder::create(array(self::ECHO_BIN, '<error>Error: ' . $exception->getMessage() . '</error>'))
                              ->getProcess()
                              ->run($callback);

                throw new CriticalToolException($exception->getMessage());
            }
        }
    }

    /**
     * @return array
     */
    public function extractCommittedFiles()
    {
        //TODO Improve this function and include it the related hooks
        $output = array();
        $rc = 0;

        exec('git rev-parse --verify HEAD 2> /dev/null', $output, $rc);
        $against = '4b825dc642cb6eb9a060e54bf8d69288fbee4904';

        if ($rc == 0) {
            $against = 'HEAD';
        }
        exec("git diff-index --cached --name-status $against | egrep '^(A|M)' | awk '{print $2;}'", $output);

        return $output;
    }

    /**
     * @param ToolInterface $tool
     * @param callable      $callback
     *
     * @throws \Symfony\Component\Process\Exception\LogicException
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     */
    private function printToolName(ToolInterface $tool, callable $callback)
    {
        ProcessBuilder::create(array(self::ECHO_BIN, '<info>'))
                      ->getProcess()
                      ->run($callback);
        ProcessBuilder::create(array(self::FIGLET_BIN, $tool->getName()))
                      ->getProcess()
                      ->run($callback);
        ProcessBuilder::create(array(self::ECHO_BIN, '</info>'))
                      ->getProcess()
                      ->run($callback);
    }
}