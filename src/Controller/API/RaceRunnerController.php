<?php

namespace App\Controller\API;

use App\Entity\Race;
use App\Entity\Runner;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class RaceRunnerController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/race/{id}/runner/add/{runner_id}', name: 'api/race_runner@addRunner', methods:['POST'])]
    /**
     * @ParamConverter("runner", options={"id" = "runner_id"})
     */
    public function addRunner(Request $request, Race $race, Runner $runner): Response
    {
        $race->addRunner($runner);

        $this->entityManager->flush();
        
        return $this->json([
            'status' => 'ok',
            'code' => 200,
            'race' => $race,
        ]);
    }
}
