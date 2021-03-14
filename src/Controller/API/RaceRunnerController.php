<?php

namespace App\Controller\API;

use App\Entity\Race;
use App\Entity\RaceResult;
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

    #[Route('/api/race/{id}/runner/{runner_id}/result/add', name: 'api/race_runner@resultAdd', methods:['POST'])]
    /**
     * @ParamConverter("runner", options={"id" = "runner_id"})
     */
    public function addResult(Request $request, Race $race, Runner $runner): Response
    {
        $requestData = $request->toArray();
        $result = new RaceResult();

        $result->setRace($race);
        $result->setRunner($runner);
        $result->setStartTime(
            (new \DateTime(
                $race->getDate()->format('Y-m-d') . ' ' . $requestData['startTime']
            ))
        );
        $result->setFinishTime(
            (new \DateTime(
                $race->getDate()->format('Y-m-d') . ' ' . $requestData['finishTime']
            ))
        );
        
        $this->entityManager->persist($result);
        $this->entityManager->flush();

        return $this->json([
            'status' => 'ok',
            'code' => 200,
            'result' => $result,
        ]);
    }

    #[Route('/api/ranking/general', name: 'api/ranking@get_general_ranking', methods:['GET'])]
    public function getGeneralRanking(): Response
    {
        $entityRepository = $this->entityManager->getRepository(RaceResult::class);
        $raceEntityRepository = $this->entityManager->getRepository(Race::class);

        $races = $raceEntityRepository->findAll();

        $rankingResults = [];
        foreach ($races as $race) {
            $raceResult = $entityRepository->getGeneralRankingByRace($race);

            array_walk($raceResult, function(&$result, $index) {
                $result['position'] = ++$index;
            });

            $rankingResults[] = [
                'race' => [
                    'id' => $race->getId(),
                    'type' => $race->getType()
                ],
                'results' => $raceResult
            ];
        }
        
        return $this->json([
            'status' => 'ok',
            'code' => 200,
            'rankings' => $rankingResults,
        ]);
    }
}
