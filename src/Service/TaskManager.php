<?php

namespace App\Service;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;

class TaskManager
{
    public $taskRepository;
    public $entityManager;

    public function __construct(TaskRepository $taskRepository, EntityManagerInterface $entityManager)
    {
        $this->taskRepository = $taskRepository;
        $this->entityManager = $entityManager;
    }

    public function addTask($nameTask): void
    {
        $task = new Task();
        $task->setName($nameTask);

        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }

    public function deleteDoneTasks(): void
    {
        $tasks = $this->taskRepository->getDoneTasks();
        foreach ($tasks as $value) {
            $this->entityManager->remove($value);
        }
        $this->entityManager->flush();
    }
}