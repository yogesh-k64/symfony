<?php

namespace App\Controller;

use App\Repository\PlayerRepository;
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
    #[Route('/player', name: 'app_player',methods:['POST'])]

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
}
