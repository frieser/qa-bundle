<?php
namespace Frieserlabs\Bundle\QABundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ProcessBuilder;
use Frieserlabs\Bundle\QABundle\Exception\CriticalToolException;
use Frieserlabs\Bundle\QABundle\QualityTester\QualityTester;

class QualityCommand extends Command
{
    /** @var  QualityTester */
    protected $qualityTester;

    /** @var  OutputInterface */
    protected $output;

    /** @var  InputInterface */
    protected $input;

    /**
     * QAToolCommand constructor.
     *
     * @param QualityTester $qualityScanner
     */
    public function __construct(QualityTester $qualityScanner)
    {
        parent::__construct();
        $this->qualityTester = $qualityScanner;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('frieserlabs:quality:check')
            ->setDescription('Runs the configured quality tools over the last staged files in the project')
            ->addArgument('hook', InputArgument::REQUIRED, 'The git hook that launches the quality scanner');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->input = $input;
        $hook = $input->getArgument('hook');

        $process = ProcessBuilder::create(array('figlet', 'QA Bundle'))
                                 ->getProcess();

        $process->run(function ($type, $buffer) {
                $this->output->write('<comment>' . $buffer . '</comment>');
        });

        try {
            $this->qualityTester->test(
                $hook,
                function ($type, $buffer) {
                    if($this->input->getOption('verbose')){
                        $this->output->write($buffer);
                    }
                }
            );
        } catch (CriticalToolException $exception) {
            $this->output->writeln('<error>Try to fix the violations to continue or disable the critical config flag.</error>');
            exit(1);
        }
        $this->output->writeln('<info>All done. You can continue.</info>');
    }
}