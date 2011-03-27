<?php

namespace BeSimple\GearmanBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Francis Besset <francis.besset@gmail.com>
 */
class RunWorkerCommand extends Command
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('gearman:run-worker')
            ->setDefinition(array(
                new InputArgument('worker', InputArgument::REQUIRED, 'The gearman worker service'),
            ))
            ->setDescription('Run a gearman worker')
            ->setHelp(<<<EOF
The <info>gearman:run-worker worker.service.name</info> run a gearman worker.

<info>./app/console run:worker worker.service.name</info>
EOF
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $worker = $this->container->get($input->getArgument('worker'));
        $worker->execute();
    }
}
