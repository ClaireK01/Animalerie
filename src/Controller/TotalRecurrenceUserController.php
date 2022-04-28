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

    private UserRepository $userRepository;
    private CommandRepository $commandRepository;

    public function __construct(UserRepository $userRepository,  CommandRepository $commandRepository)
    {
        $this->userRepository = $userRepository;
        $this->commandRepository = $commandRepository;
    }
    public function __invoke(Request $request)
    {

        $dateMinString = $request->query->get('date_min');
        $dateMaxString = $request->query->get('date_max');

        $dateMin = new DateTime($dateMinString);
        $dateMax = new DateTime($dateMaxString);

        $newUsersEntities = $this->commandRepository->getAllCommandsByStatusWithNewUsers($dateMin, $dateMax, 200, 300, 400, 500);
        $nbNewUsers = count($newUsersEntities);
        $OldUsersEntities = $this->commandRepository->getAllCommandsByStatusWithOldUsers($dateMin, 200, 300, 400, 500);
        $nbOldUsers = count($OldUsersEntities);
    }
}
