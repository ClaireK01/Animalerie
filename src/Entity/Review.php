<?php

namespace App\Entity;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
#[APIResource(
    normalizationContext: ['groups'=>['review']],
    collectionOperations:['get', 'post'],
    itemOperations:['get']
)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups("product", "review", "user")]
    private $id;

    ##########

    #[ORM\Column(type: 'integer')]
    #[Groups("product", "user", "review")]
    #[ Assert\NotBlank(
        message: 'Votre champ est vide'
    ),
    Assert\NotNull(
        message: 'Merci de rentrer une valeur'
    ),
    Assert\Assert\PositiveOrZero(
        message : 'La note ne peut pas être négatif'
    ),
    Assert\Type(
        type: 'integer',
        message: 'Merci de rentrer un chiffre'
    )]
    private $note;

    ##########

    #[ORM\Column(type: 'datetime')]
    #[Groups("review", "user")]
    #[Assert\DateTime(
        message: "Merci de rentrer une date valide."
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
    private $createdAt;

    ##########

    #[ORM\Column(type: 'text')]
    #[Groups("review")]
    #[Assert\Length(
        min:2,
        max:350,
        minMessage: 'Votre message dois faire 4 caractères minimum',
        maxMessage: 'Votre message est trop long'
    ),
    Assert\NotBlank(
        message: 'Votre champ est vide'
    ),
    Assert\NotNull(
        message: 'Merci de rentrer une valeur'
    )]
    private $content;

    ##########

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'Review')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("review")]
    private $user;

    ##########

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("review")]
    private $product;

    ##########

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
