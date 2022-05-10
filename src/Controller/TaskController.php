<?php

namespace App\Controller;

use App\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Task;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\TaskRepository;

class TaskController extends AbstractController
{
    /**
     * @Route("/task/add", name="add_task")
     */
    public function addTask(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $task = new Task();
        $task->setName('buy a keyboard');

        $entityManager->persist($task);
        $entityManager->flush();

        return $this->redirectToRoute('show_all_task');
    }

    /**
     * @Route("/task/new", name="new_task")
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $doctrine->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

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
    public function show(ManagerRegistry $doctrine, int $id): Response
    {
        $product = $doctrine->getRepository(Task::class)->find($id);

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
    public function showAllTask(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Task::class);
        $tasks = $repository->findAll();

        return $this->render('task/index.html.twig', [
            'project_title' => 'Все задачи',
            'tasks' => $tasks,
        ]);
    }

    /**
     * @Route("/task/active", name="active_task")
     */
    public function showActiveTask(ManagerRegistry $doctrine): Response
    {
        $tasks = $doctrine->getRepository(Task::class)->activeTask();

        return $this->render('task/index.html.twig', [
            'project_title' => 'Астивные задачи',
            'tasks' => $tasks,
        ]);
    }

    /**
     * @Route("/task/made", name="made_task")
     */
    public function showMadeTask(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Task::class);
        $tasks = $repository->findBy([
            'done' => 1,
        ]);

        return $this->render('task/index.html.twig', [
            'project_title' => 'Выполненные задачи',
            'tasks' => $tasks,
        ]);
    }

    /**
     * @Route("/task/edit/{id}")
     */
    public function update(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);

        if (!$task) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $task->setName('New product name!');
        $entityManager->flush();

        return $this->redirectToRoute('show_all_task');
    }

    /**
     * @Route("/task/delete/{id}", name="delete_task", requirements={"id"="\d+"})
     */
    public function deleteTask(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);
        $entityManager->remove($task);
        $entityManager->flush();
        return $this->redirectToRoute('show_all_task');
    }

    /**
     * @Route("/task/delete/completed", name="delete_completed_task")
     */
    public function deleteCompletedTask(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $tasks = $entityManager->getRepository(Task::class)->findBy([
            'done' => 1,
        ]);
        foreach ($tasks as $value) {
            $entityManager->remove($value);
        }
        $entityManager->flush();
        return $this->redirectToRoute('show_all_task');
    }

    /**
     * @Route("/task/completed/{id}", name="completed_task")
     */
    public function comletedTask(ManagerRegistry $doctrine, $id): Response
    {
        $entityManager = $doctrine->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);

        if (!$task) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $task->setDone(true);
        $entityManager->flush();

        return $this->redirectToRoute('show_all_task');
    }

    /**
     * @Route("/task/take_off/{id}", name="take_off_task")
     */
    public function takeOffTask(ManagerRegistry $doctrine, $id): Response
    {
        $entityManager = $doctrine->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);

        if (!$task) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $task->setDone(false);
        $entityManager->flush();

        return $this->redirectToRoute('show_all_task');
    }

}
