<?php

namespace App\Controller;

use App\Repository\ClientsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ClientInfosForListController extends AbstractController
{
    protected $ccsrepos;
    public function __construct(ClientsRepository $ccsrepos) {
        $this->ccsrepos = $ccsrepos;
    }
    public function __invoke()
    {
        $result = $this->ccsrepos->getClientInfoForList();

       return $result;
    }
   

    
}
