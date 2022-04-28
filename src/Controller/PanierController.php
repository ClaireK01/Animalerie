<?php

namespace App\Controller;

use App\Repository\CommandRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{

    private CommandRepository $commandRepository;

    public function __construct(CommandRepository $commandRepository)
    {
        $this->commandRepository = $commandRepository;
    }
    public function __invoke(Request $request)
    {
        $dateMinString = $request->query->get('date_min');
        $dateMaxString = $request->query->get('date_max');

        $dateMin = new DateTime($dateMinString);
        $dateMax = new DateTime($dateMaxString);
        
        $PanierEntities = $this->commandRepository->getAllCommandByStatus($dateMin, $dateMax, 100);
        dump($PanierEntities);
        return count($PanierEntities);
    }
}
