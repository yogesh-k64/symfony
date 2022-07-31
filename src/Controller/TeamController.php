<?php

namespace App\Controller;

use App\Repository\TeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    private $teamRepository;

    public function __construct(TeamRepository $teamRepository)
    {
        $this->teamRepository = $teamRepository;
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
            'sportId'=> $team->getSportId()
        ];

        return new JsonResponse($data,Response::HTTP_OK);
    }

    #[Route(path:'/teams', name:'get_all_team',methods:['GET'])]
    
    public function getAllTeam():JsonResponse{

        $allTeam = $this->teamRepository->findAll();
        $data = [];
        foreach ($allTeam as $team) {
            $data[]=[
                'name' => $team->getName(),
                'sportId' => $team->getSportId(),
                'id' => $team->getId()
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
        return new JsonResponse(['status' => 'Team deleted succesfully',Response::HTTP_NO_CONTENT]);
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
}
