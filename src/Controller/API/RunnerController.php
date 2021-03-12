<?php

namespace App\Controller\API;

use App\Entity\Runner;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RunnerController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/runner', name: 'api/runner@index', methods:['GET'])]
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

    #[Route('/api/runner', name: 'api/runner@new', methods:['POST'])]
    public function new(Request $request): Response
    {
        $requestData = $request->toArray();
        $runner = new Runner();

        $runner->setName($requestData['name']);
        $runner->setCpf($requestData['cpf']);
        $runner->setBirthdate((new \DateTime($requestData['birthdate'])));

        $this->entityManager->persist($runner);
        $this->entityManager->flush();

        return $this->json([
            'status' => 'ok',
            'code' => 200,
            'runner' => $this->container->get('serializer')->normalize($runner, null),
        ]);
    }
}
