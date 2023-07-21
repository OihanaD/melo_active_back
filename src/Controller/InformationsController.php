<?php

namespace App\Controller;

use App\Repository\ClientsCoachingSessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class InformationsController extends AbstractController
{
    protected $ccsrepos;
    public function __construct(ClientsCoachingSessionRepository $ccsrepos) {
        $this->ccsrepos = $ccsrepos;
    }
    #[Route('/informations/{year}/{month}/{firstday}', name: 'app_informations')]
    public function __invoke(Request $request)
    {
        $year = $request->attributes->get('year'); 
        $month = $request->attributes->get('month'); 
        $firstday = $request->attributes->get('firstday'); 
        $result = $this->ccsrepos->search($year, $month, $firstday);
        // if($responseData['user_image']){
        //    $responseData = "ok";
        // }
        // dd('ok');
       return $result;

    }
}
