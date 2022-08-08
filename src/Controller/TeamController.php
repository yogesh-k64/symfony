<?php

namespace App\Controller;

use App\Entity\Player;
use App\Entity\Team;
use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class TeamController extends AbstractController
{
    private $teamRepository;
    private $playerRepository;

    public function __construct(TeamRepository $teamRepository,PlayerRepository $playerRepository)
    {
        $this->teamRepository = $teamRepository;
        $this->playerRepository = $playerRepository;


    }

    #[Route('/teams', name: 'add_team', methods:['POST','HEAD'])]

    public function add(Request $request):JsonResponse
    {
        $team = json_decode($request->getContent(),true); 

        $name = $team['name'];
        $sportId = $team['sportId'];

        if(empty($name) || empty($sportId)){
           throw new NotFoundHttpException('Can not add empty value');
        }

        $addedTeam = $this->teamRepository-> addToTeam($name,$sportId);
        return $this->json($addedTeam,Response::HTTP_CREATED);
    }
    
    #[Route(path:'/teams/{id}',name:'get_one_team',methods:['GET'])]
    
    public function getTeam($id):JsonResponse
    {
        $team = $this->teamRepository->findOneBy(['id' => $id]);

        if (!$team){
            throw new NotFoundHttpException('Can not find team');
        }

        $data = [
            'name' => $team->getName(),
            'sportId'=> $team->getSportId(),
            'id'=>$team->getId()
        ];
        return new JsonResponse($data,Response::HTTP_OK);
    }

    #[Route(path:'/teams', name:'get_all_team',methods:['GET'])]
    
    public function getAllTeam():JsonResponse{

        $allTeam = $this->teamRepository->findAll();
        $data = [];
        foreach ($allTeam as $team) {
            $data []=[
                'name' => $team->getName(),
                'sportId' => $team->getSportId(),
                'id' => $team->getId(),
                'players'=>$team->getPlayers()
            ];
        }
        return new JsonResponse($data,Response::HTTP_OK);
    }
    
    #[Route(path:'/teams/{id}',name:'delete_team',methods:['DELETE'])]

    public function deleteTeam($id):JsonResponse 
    {
        $team = $this->teamRepository->findOneBy(['id' => $id]);

        if(!$team){
            throw new NotFoundHttpException('Team does not exit');
        }

        $this->teamRepository->removeTeam($team);
        
        return $this->json(["message"=>"team deleted"],Response::HTTP_OK);
    }

    #[Route(path:'/teams/{id}',name:'update_team',methods:['PUT'])]

    public function updateTeam ($id, Request $request): JsonResponse
    {
        $team = $this->teamRepository->findOneBy(['id'=>$id]);
        $data = json_decode($request->getContent(),true);

        empty($data['name']) ? true : $team->setName($data['name']);
        empty($data['sportId']) ? true : $team->setSportId($data['sportId']);

        $updatedTeam = $this->teamRepository->updateTeam($team);
        return $this->json($updatedTeam,Response::HTTP_OK);

    }
    #[Route('/teams/{id}/members', name: 'add_player_to_team', methods: ['POST'])]
    public function addPlayerToTeam(Team $team, ManagerRegistry $doctrine, Request $request)
    {
        $entityManager = $doctrine->getManager();

        $data = json_decode($request->getContent(), true);

        $newPlayer = new Player();
        $newPlayer->setFirstName($data['firstName'])
        ->setLastName($data['lastName'])
        ->setPhoneNumber($data['phoneNumber'])
        ->addTeam($team);

        $entityManager->persist($newPlayer);
        $entityManager->flush();

        $results = [
            'id' => $team->getId(),
            'name' => $team->getId(),
            'sportId' => $team->getSportId()
        ];

        $players = $team->getPlayers()->toArray();

        foreach ($players as $player) {
            $results['players'][] =
                [
                    'firstName' => $player->getFirstName(),
                    'lastName' => $player->getLastName(),
                    'phoneNumber' => $player->getPhoneNumber(),
                ];
        };

        return $this->json($results, 200);
    }

    #[Route('/teams/{id}/members',name:'get_all_team_members',methods:['GET'])]
    public function getAllTeamMembers($id)
    {
       $players = $this->teamRepository->getTeamPlayers($id);
    //    dd($players);
    //    return $this->json($players,200);
    }
}
