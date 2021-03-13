<?php

namespace App\Controller\API;

use App\Entity\Race;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RaceController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/race', name: 'api/race@index', methods:['GET'])]
    public function index(): Response
    {
        $entityRepository = $this->entityManager->getRepository(Race::class);

        $runners = $entityRepository->findAll();
        
        return $this->json([
            'status' => 'ok',
            'code' => 200,
            'races' => $runners,
        ]);
    }

    #[Route('/api/race', name: 'api/race@new', methods:['POST'])]
    public function new(Request $request, ValidatorInterface $validator): Response
    {
        $requestData = $request->toArray();
        $race = new Race();

        $race->setType($requestData['type']);
        $race->setDate((new \DateTime($requestData['date'])));

        $errors = $validator->validate($race);

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

        $this->entityManager->persist($race);
        $this->entityManager->flush();

        return $this->json([
            'status' => 'ok',
            'code' => 200,
            'race' => $this->container->get('serializer')->normalize($race, null),
        ]);
    }
}
