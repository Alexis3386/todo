<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class TaskController extends AbstractController
{

    #[Route("/tasks", name: "task_list")]
    public function listAction(TaskRepository $repository): Response
    {
        return $this->render('task/list.html.twig', ['tasks' => $repository->findBy([], ['createdAt' => 'DESC'])]);
    }


    #[IsGranted('ROLE_USER')]
    #[Route("/tasks/create", name: "task_create")]
    public function createAction(Request $request, EntityManagerInterface $em): RedirectResponse|Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($this->getUser());
            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route("/tasks/{id}/edit", name: "task_edit")]
    public function editAction(Task $task, Request $request, EntityManagerInterface $em): RedirectResponse|Response
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render(
            'task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
            ]
        );
    }

    #[Route("/tasks/{id}/toggle", name: "task_toggle")]
    public function toggleTaskAction(Task $task, EntityManagerInterface $em): RedirectResponse
    {
        $task->toggle(!$task->isDone());
        $em->flush();

        if ($task->isDone() === true) {
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
        }

        return $this->redirectToRoute('task_list');
    }

    #[Route("/tasks/{id}/delete", name: "task_delete", methods: ['post'])]
    public function deleteTaskAction(Task $task, EntityManagerInterface $em, Request $request): RedirectResponse
    {
        $this->denyAccessUnlessGranted('CAN_DELETE', $task);

        $submittedToken = $request->request->get('token');
        if ($this->isCsrfTokenValid('delete-task' . $task->getId(), $submittedToken)) {
            $em->remove($task);
            $em->flush();
        }

        $this->addFlash('success', 'La tâche a bien été supprimée.');
        return $this->redirectToRoute('task_list');
    }
}
