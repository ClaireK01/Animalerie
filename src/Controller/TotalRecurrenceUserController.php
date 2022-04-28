<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TotalRecurrenceUserController extends AbstractController
{

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
    }
    public function __invoke()
    {
    }
}
