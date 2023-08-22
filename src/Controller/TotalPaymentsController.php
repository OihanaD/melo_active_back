<?php

namespace App\Controller;

use App\Repository\ClientsCoachingSessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TotalPaymentsController extends AbstractController
{
    protected $ccsrepos;
    public function __construct(ClientsCoachingSessionRepository $ccsrepos) {
        $this->ccsrepos = $ccsrepos;
    }
    public function __invoke(Request $request)
    {
        $year = $request->attributes->get('year'); 
        $month = $request->attributes->get('month'); 
        $result = $this->ccsrepos->paymentsPerMonthPayed($month, $year);

       return $result;

    }
}
