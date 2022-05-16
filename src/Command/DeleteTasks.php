<?php


namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use App\Service\TaskManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Repository\TaskRepository;


class DeleteTasks extends Command
{
    private $taskManager;

    public function __construct(TaskManager $taskManager)
    {
        $this->taskManager = $taskManager;

        parent::__construct();
    }

    protected static $defaultName = 'delete-tasks';

    protected static $defaultDescription = 'Delete completed tasks';

    protected function configure(): void
    {
        $this
            ->setHelp('This command deletes all completed tasks...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Deleting completed tasks...');

        $this->taskManager->deleteDoneTasks();

        $output->writeln('All completed tasks have been deleted...');

        return Command::SUCCESS;
    }

}