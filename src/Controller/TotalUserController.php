<?php

namespace App\Controller;

use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TotalUserController extends AbstractController
{

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(Request $request)
    {
        $dateMinString = $request->query->get('date_min');
        $dateMaxString = $request->query->get('date_max');

        $dateMin = new Datetime($dateMinString);
        $dateMax = new DateTime($dateMaxString);
        dump($dateMax);

        $userEntities = $this->userRepository->getAllUserByDate($dateMin, $dateMax);
        return count($userEntities);
    }
}
