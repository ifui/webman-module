<?php

namespace Ifui\WebmanModule\Commands;

use Exception;
use Ifui\WebmanModule\Concerns\Console\InteractsWithIO;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Command extends \Symfony\Component\Console\Command\Command
{
    use InteractsWithIO;

    /**
     * The SymfonyStyle implementation.
     *
     * @var SymfonyStyle
     */
    public SymfonyStyle $symfony;

    /**
     * The input interface implementation.
     *
     * @var InputInterface
     */
    public InputInterface $input;

    /**
     * The output interface implementation.
     *
     * @var OutputInterface
     */
    public OutputInterface $output;

    /**
     * Run the console command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws Exception
     */
    public function run(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;
        $this->symfony = new SymfonyStyle($input, $output);

        return parent::run($this->input, $this->output);
    }

    /**
     * Overwrite execute method.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return self::SUCCESS;
    }
}