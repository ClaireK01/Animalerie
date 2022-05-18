<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CommandRepository;
use DateTime;

class CommandController extends AbstractController
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

        $CommandEntities = $this->commandRepository->getAllCommandByStatus($dateMin, $dateMax, 200, 300);
        dump($CommandEntities);
        $total = 0;
        foreach ($CommandEntities as $command) {
            $total = $total + $command->getTotalPrice();
        }
        return $total;
    }
}
