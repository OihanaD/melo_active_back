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
    public function __invoke(Request $request)
    {
        $year = $request->attributes->get('year'); 
        $month = $request->attributes->get('month'); 
        $firstday = $request->attributes->get('firstday'); 
        if ($month < 10 && substr($month, 0, 1) !== '0') {
            $month = '0' . $month;
        }
        if ($firstday < 10 && substr($firstday, 0, 1) !== '0') {
            $firstday = '0' . $firstday;
        }
        
        $result = $this->ccsrepos->search($year, $month, $firstday);

       return $result;

    }
}
