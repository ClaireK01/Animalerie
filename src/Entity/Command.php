<?php

namespace App\Entity;

use App\Controller\CommandController;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use App\Controller\CommandNumberController;
use App\Controller\ConvertedBasketController;
use App\Controller\ConvertedCommandController;
use App\Controller\PanierController;
use App\Controller\TotalRecurrenceUserController;

#[ORM\Entity(repositoryClass: CommandRepository::class)]
#[APIResource(
    normalizationContext: ['groups' => ['command']],
    collectionOperations: [
        'get',
        'post',
        'CountCommand' => [
            'method' => 'GET',
            'path' => '/commands/total_commands',
            'controller' => CommandController::class
        ],
        'CountPanier' => [
            'method' => 'GET',
            'path' => 'commands/total_paniers',
            'controller' => PanierController::class
        ],
        'CountCommandNumber' => [
            'method' => 'GET',
            'path' => '/commands/total_number_commands',
            'controller' => CommandNumberController::class
        ],
        'CountConvertedBasket' => [
            'method' => 'GET',
            'path' => '/commands/converted_baskets',
            'controller' => ConvertedBasketController::class
        ],
        'CountConvertedCommand' => [
            'method' => 'GET',
            'path' => '/commands/converted_commands',
            'controller' => ConvertedCommandController::class
        ],
        'TotalRecurrenceCommand' => [
            'method' => 'GET',
            'path' => '/commands/recurence_commands',
            'controller' => TotalRecurrenceUserController::class
        ]
    ],
    itemOperations: ['get']
)]
#[ApiFilter(DateFilter::class, properties: ['createdAt'])]
class Command
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["adress", "command", "product", "user"])]
    private $id;

    ##########

    #[ORM\Column(type: 'integer')]
    #[Groups(["adress", "command", "product", "user"])]
    // #[
    //     Assert\NotNull(
    //         message: 'Merci de rentrer une valeur'
    //     ),
    //     Assert\NotBlank(
    //         message: 'Votre champ est vide'
    //     )
    // ]
    private $numCommand;

    ##########

    #[ORM\Column(type: 'datetime')]
    #[Groups(["command"])]
    #[
        Assert\Type(
            type: "datetime",
            message: "Merci de rentrer un format de date valide."
        ),
        Assert\GreaterThan(
            value: 'today',
            message: 'Merci de rentrer une date valide.'
        ),
        Assert\NotNull(
            message: "Merci de rentrer une valeur."
        ),
        Assert\NotBlank(
            message: 'Votre champ est vide.'
        )
    ]
    private $createdAt;

    ##########

    #[ORM\Column(type: 'integer')]
    #[Groups(["adress", "command", "product"])]

    private $status;

    ##########

    #[ORM\Column(type: 'integer')]
    #[Groups(["command"])]
    #[
        Assert\NotNull(
            message: 'Merci de rentrer une valeur'
        ),
        Assert\NotBlank(
            message: 'Votre champ est vide'
        ),
        Assert\PositiveOrZero(
            message: "Le prix total ne peut pas être négatif"
        ),
        Assert\Type(
            type: 'integer',
            message: 'Merci de rentrer un nombre'
        )
    ]
    private $totalPrice;

    ##########

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commands')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["command"])]
    private $user;

    ##########

    #[ORM\ManyToMany(targetEntity: Product::class, inversedBy: 'commands')]
    #[Groups(["command"])]
    private $products;

    ##########

    #[ORM\ManyToOne(targetEntity: Adress::class, inversedBy: 'commands')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["command"])]
    private $adress;

    ##########

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumCommand(): ?int
    {
        return $this->numCommand;
    }

    public function setNumCommand(int $numCommand): self
    {
        $this->numCommand = $numCommand;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(int $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        $this->products->removeElement($product);

        return $this;
    }

    public function getAdress(): ?Adress
    {
        return $this->adress;
    }

    public function setAdress(?Adress $adress): self
    {
        $this->adress = $adress;

        return $this;
    }
}
