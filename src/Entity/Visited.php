<?php

namespace App\Entity;

use App\Controller\VisitedController;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\VisitedRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VisitedRepository::class)]
#[ApiResource(
    collectionOperations:[
        'get',
        'post',
        'countAllVisits' => [
            'method' => 'GET',
            'path' => 'visited/visited_total_item',
            'controller' => VisitedController::class
        ]
    ],
    itemOperations:['get']
)]
class Visited
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    ##########

    #[ORM\Column(type: 'datetime')]
    #[Assert\Type(
        type: 'datetime',
        message: 'Merci de rentrer un format de date valide'
    ), 
    Assert\GreaterThan(
        value:'today',
        message:'Merci de rentrer une date valide.'
    ), Assert\NotNull(
        message: "Merci de rentrer une valeur."
    ),
    Assert\NotBlank(
        message: 'Votre champ est vide.'
    )]
    private $visitedAt;

    ##########

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVisitedAt(): ?\DateTimeInterface
    {
        return $this->visitedAt;
    }

    public function setVisitedAt(\DateTimeInterface $visitedAt): self
    {
        $this->visitedAt = $visitedAt;

        return $this;
    }
}
