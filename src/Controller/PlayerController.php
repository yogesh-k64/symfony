<?php

namespace App\Controller;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PlayerController extends AbstractController
{
    public $playerRepository;

    public function __construct(PlayerRepository $playerRepository)
    {
        $this->playerRepository = $playerRepository;
    }
    
    #[Route('/players', name: 'add_player',methods:['POST'])]

    public function add(Request $request):JsonResponse
    {   
        $team = json_decode($request->getContent(),true); 
        $data = $request->get('var',5);
        var_dump($data);
        $firstName = $team['firstName'];
        $lastName = $team['lastName'];
        $phoneNumber= $team['phoneNumber'];

        if(empty($firstName) || empty($lastName) || empty($phoneNumber) ){
           throw new NotFoundHttpException('Can not add empty value');
        };

        $addedPlayer = $this->playerRepository-> addToPlayer($firstName,$lastName,$phoneNumber);
        return $this->json($addedPlayer,Response::HTTP_CREATED);
    }

    #[Route('/players', name: 'get_players', methods: ['GET'])]
    public function getAllPlayers():JsonResponse
    {
        $players = $this->playerRepository->findAll();

        // $json = $serializer->serialize(
        //     $user,
        //     'json', ['groups' => ['user','entreprise' /* if you add "user_detail" here you get circular reference */]]
        // );
        return $this->json($players);
    }

    #[Route('/players/{id}', name: 'get_player', methods: ['GET'])]
    public function getPlayer(Player $player):JsonResponse
    {
        return $this->json($player,);
    }
    
    #[Route('/players/{id}/teams', name: 'get_player', methods: ['GET'])]
    public function getPlayerTeams(Player $player):JsonResponse
    {
        $results =[
            'id'=>$player->getId(),
            'firstName'=>$player->getFirstName(),
            'lastName'=>$player->getLastName(),
        ];
        $teams = $player->getTeams()->toArray();
        foreach($teams as $team){
            $results['teams'][]=[
                'id'=>$team->getId(),
                'name'=>$team->getName(),
                'sportId'=>$team->getSportId(),
            ];
        }
        return $this->json($results);
    }
    
}
