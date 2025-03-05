<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class TaskController extends AbstractController
{
    #[Route('/task', name: 'app_task')]
    public function createTask(Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Task();
        
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('task/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/task/delete/{id}', name: 'app_task_delete', methods: ['POST'])]
    public function deleteTask(Request $request, Task $task, EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        if ($task->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You do not have the right to delete this task.');
        }

        $submittedToken = $request->request->get('_token');
        if (!$csrfTokenManager->isTokenValid(new CsrfToken('delete_task', $submittedToken))) {
            throw $this->createAccessDeniedException('Faulty CSRF token.');
        }

        $entityManager->remove($task);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }

    #[Route('/task/toggle/{id}', name: 'app_task_toggle', methods: ['POST'])]
    public function toggleTask(Task $task, EntityManagerInterface $entityManager): Response
    {
        if ($task->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You do not have the right to delete this task.');
        }

        $task->setCompleted(!$task->isCompleted());
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }

    
}
?>