<?php

namespace App\Controller\API;

use App\Entity\Runner;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    public function new(Request $request, ValidatorInterface $validator): Response
    {
        $requestData = $request->toArray();
        $runner = new Runner();

        $runner->setName($requestData['name']);
        $runner->setCpf($requestData['cpf']);
        $runner->setBirthdate((new \DateTime($requestData['birthdate'])));

        $errors = $validator->validate($runner);

        if (count($errors) > 0) {
            $errorMessages = [];

            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            return $this->json([
                'status' => 'error',
                'code' => 400,
                'errors' => $errorMessages
            ], 400);
        }

        $this->entityManager->persist($runner);
        $this->entityManager->flush();

        return $this->json([
            'status' => 'ok',
            'code' => 200,
            'runner' => $this->container->get('serializer')->normalize($runner, null),
        ]);
    }

    #[Route('/api/runner/batch', name: 'api/runner@new_batch', methods:['POST'])]
    public function newBatch(Request $request, ValidatorInterface $validator): Response
    {
        $requestData = $request->toArray();

        $runnersData = array_map(function($runnerData) use ($validator) {
            $runner = new Runner();
    
            $runner->setName($runnerData['name']);
            $runner->setCpf($runnerData['cpf']);
            $runner->setBirthdate((new \DateTime($runnerData['birthdate'])));
    
            $errors = $validator->validate($runner);
    
            if (count($errors) > 0) {
                $errorMessages = [];
    
                foreach ($errors as $error) {
                    $errorMessages[] = $error->getMessage();
                }
    
                return $this->json([
                    'status' => 'error',
                    'code' => 400,
                    'errors' => $errorMessages
                ], 400);
            }
    
            $this->entityManager->persist($runner);
            $this->entityManager->flush();

            return $this->container->get('serializer')->normalize($runner, null);
        }, $requestData['runners']);
        

        return $this->json([
            'status' => 'ok',
            'code' => 200,
            'runners' => $runnersData,
        ]);
    }
}
