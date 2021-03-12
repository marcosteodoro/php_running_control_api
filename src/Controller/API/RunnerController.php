<?php

namespace App\Controller\API;

use App\Entity\Runner;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RunnerController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/runner', name: 'api/runner')]
    public function index(): Response
    {
        $entityRepository = $this->entityManager->getRepository(Runner::class);

        $runners = $entityRepository->findAll();
        
        return $this->json([
            'status' => 'ok',
            'code' => 200,
            'runners' => $runners,
        ]);
    }
}
