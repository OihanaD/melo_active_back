<?php

namespace App\Controller;

use App\Repository\ClientsCoachingSessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentsController extends AbstractController
{
    protected $ccsrepos;
    public function __construct(ClientsCoachingSessionRepository $ccsrepos) {
        $this->ccsrepos = $ccsrepos;
    }
    public function __invoke()
    {
         
        $result = $this->ccsrepos->payments();

       return $result;

    }
}
