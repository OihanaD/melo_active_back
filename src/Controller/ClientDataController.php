<?php

namespace App\Controller;

use App\Repository\ClientsCoachingSessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientDataController extends AbstractController
{
   
    protected $ccsrepos;
    public function __construct(ClientsCoachingSessionRepository $ccsrepos) {
        $this->ccsrepos = $ccsrepos;
    }
    #[Route('/client/{id}', name: 'app_client_data')]
    public function __invoke(Request $request)
    {
        $id = $request->attributes->get('id'); 
        
        $result = $this->ccsrepos->getClientDataById($id);

       return $result;

    }
}
