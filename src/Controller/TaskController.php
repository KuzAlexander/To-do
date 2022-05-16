<?php

namespace App\Controller;

use App\Form\TaskType;
use App\Service\TaskManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Task;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\TaskRepository;

class TaskController extends AbstractController
{
    private $taskRepository;
    private $entityManager;
    private $taskManager;

    public function __construct(TaskManager $taskManager)
    {
        $this->taskRepository = $taskManager->taskRepository;
        $this->entityManager = $taskManager->entityManager;
        $this->taskManager = $taskManager;
    }

    /**
     * @Route("/task/add/{nameTask}", name="add_task")
     */
    public function addTask(string $nameTask): Response
    {
        $this->taskManager->addTask($nameTask);
        return $this->redirectToRoute('show_all_task');
    }

    /**
     * @Route("/task/new", name="new_task")
     */
    public function new(Request $request): Response
    {
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($task);
            $this->entityManager->flush();

            return $this->redirectToRoute('show_all_task');
        }

        return $this->renderForm('task/new.html.twig', [
            'project_title' => 'Добавить задачу',
            'form' => $form,
        ]);
    }

    /**
     * @Route("/task/show/{id}", requirements={"id"="\d+"})
     */
    public function show(int $id): Response
    {
        $product = $this->taskRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        return new Response('Check out this great product: ' . $product->getName());
    }

    /**
     * @Route("/task", name="show_all_task")
     */
    public function showAllTask(): Response
    {
        $tasks = $this->taskRepository->findAll();

        return $this->render('task/index.html.twig', [
            'project_title' => 'Все задачи',
            'tasks' => $tasks,
        ]);
    }

    /**
     * @Route("/task/active", name="active_task")
     */
    public function showActiveTask(): Response
    {
        $tasks = $this->taskRepository->getActiveTasks();

        return $this->render('task/index.html.twig', [
            'project_title' => 'Астивные задачи',
            'tasks' => $tasks,
        ]);
    }

    /**
     * @Route("/task/done", name="made_task")
     */
    public function showDoneTask(): Response
    {
        $tasks = $this->taskRepository->getDoneTasks();

        return $this->render('task/index.html.twig', [
            'project_title' => 'Выполненные задачи',
            'tasks' => $tasks,
        ]);
    }

    /**
     * @Route("/task/edit/{id}")
     */
    public function update(int $id): Response
    {
        $task = $this->taskRepository->find($id);

        if (!$task) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $task->setName('New product name!');
        $this->entityManager->flush();

        return $this->redirectToRoute('show_all_task');
    }

    /**
     * @Route("/task/delete/{id}", name="delete_task", requirements={"id"="\d+"})
     */
    public function deleteTask(int $id): Response
    {

        $task = $this->taskRepository->find($id);
        $this->entityManager->remove($task);
        $this->entityManager->flush();
        return $this->redirectToRoute('show_all_task');
    }

    /**
     * @Route("/task/delete/completed", name="delete_completed_task")
     */
    public function deleteCompletedTask(): Response
    {
        $this->taskManager->deleteDoneTasks();
        return $this->redirectToRoute('show_all_task');
    }

    /**
     * @Route("/task/completed/{id}", name="completed_task")
     */
    public function comletedTask($id): Response
    {
        $task = $this->taskRepository->find($id);

        if (!$task) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $task->setDone(true);
        $this->entityManager->flush();

        return $this->redirectToRoute('show_all_task');
    }

    /**
     * @Route("/task/take-off/{id}", name="take_off_task")
     */
    public function takeOffTask($id): Response
    {
        $task = $this->taskRepository->find($id);

        if (!$task) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $task->setDone(false);
        $this->entityManager->flush();

        return $this->redirectToRoute('show_all_task');
    }

}
