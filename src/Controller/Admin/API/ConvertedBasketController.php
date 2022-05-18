<?php

namespace App\Controller;

use App\Repository\CommandRepository;
use App\Repository\VisitedRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConvertedBasketController extends AbstractController
{
    private CommandRepository $commandRepository;
    private VisitedRepository $visitedRepository;

    public function __construct(CommandRepository $commandRepository, VisitedRepository $visitedRepository)
    {
        $this->commandRepository = $commandRepository;
        $this->visitedRepository = $visitedRepository;
    }
    public function __invoke(Request $request)
    {
        $dateMinString = $request->query->get('date_min');
        $dateMaxString = $request->query->get('date_max');

        $dateMin = new DateTime($dateMinString);
        $dateMax = new DateTime($dateMaxString);

        $PanierEntities = $this->commandRepository->getAllCommandByStatus($dateMin, $dateMax, 100);
        dump($PanierEntities);
        $visitedEntities = $this->visitedRepository->countAllVisits($dateMin, $dateMax);
        $nbPanier = count($PanierEntities);
        $nbVisits = count($visitedEntities);

        return ($nbPanier * 100) / $nbVisits;
    }
}
