<?php


namespace App\Command;


use App\Service\TaskManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddTask extends Command
{
    private $taskManager;

    public function __construct(TaskManager $taskManager)
    {
        $this->taskManager = $taskManager;

        parent::__construct();
    }

    protected static $defaultName = 'add-task';

    protected static $defaultDescription = 'Add a task';

    protected function configure(): void
    {
        $this
            ->setHelp('This command add a task...')
            ->addArgument('name', InputArgument::REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $nameTask = $input->getArgument('name');

        $output->writeln("Adding a task: $nameTask");

        $this->taskManager->addTask($nameTask);

        $output->writeln("Task: $nameTask - added");

        return Command::SUCCESS;
    }
}