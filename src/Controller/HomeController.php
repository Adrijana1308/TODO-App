<?php

namespace App\Controller;


use App\Entity\Task;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\TaskType;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
   {
        $user = $this->getUser();
        
        if ($user) {
            $tasks = $user->getTasks();
            
            $task = new Task();
            $form = $this->createForm(TaskType::class, $task);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $task->setUser($user);

                $entityManager->persist($task);
                $entityManager->flush();

                return $this->redirectToRoute('app_home');
            }
        } else {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'tasks' => $tasks ?? [],
            'form' => $form->createView(),
        ]);
    }

    #[Route('/', name: 'app_redirect')]
    public function redirectToHome(): RedirectResponse
    {
        return $this->redirectToRoute('app_home');
    }
}
