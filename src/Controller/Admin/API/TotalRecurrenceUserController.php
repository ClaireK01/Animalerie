<?php

namespace App\Controller;

use App\Repository\CommandRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TotalRecurrenceUserController extends AbstractController
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


        $newUsersEntities = $this->commandRepository->getAllCommandsByStatusWitNewUsers($dateMin, $dateMax, 200, 300, 400, 500);
        $nbNewUsers = count($newUsersEntities);
        $oldUsersEntities = $this->commandRepository->getAllCommandsByStatusWitOldhUsers($dateMin, $dateMax, 200, 300, 400, 500);
        $nbOldUsers = count($oldUsersEntities);

        $result = ($nbNewUsers / $nbOldUsers) * 100;

        if ($nbNewUsers == 0 || $nbOldUsers == 0) {
            return $this->json(['data' => "l'un des tableau est vide"]);
        } else {
            return $this->json(['data' => $result]);
        }
    }
}
