<?php

namespace App\Controller;

use App\Repository\VisitedRepository;
use Symfony\Component\HttpFoundation\Request;
use Datetime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VisitedController extends AbstractController
{
    private VisitedRepository $visitedRepository;

    public function __construct(VisitedRepository $visitedRepository)
    {
        $this->visitedRepository = $visitedRepository;
    }

    public function __invoke(Request $request)
    {
        dump('ici');
        $minDateString = $request->query->get('date_min');
        $maxDateString = $request->query->get('date_max');
        dump($minDateString);
        dump($maxDateString);

        $minDate = new Datetime($minDateString);
        dump($minDate);
        $maxDate = new Datetime($maxDateString);
        dump($maxDate);

        $visitEntities = $this->visitedRepository->countAllVisits($minDate, $maxDate);
        dump($visitEntities);
        dump($this->getUser());

        return count($visitEntities);
    }
}
