<?php

namespace App\Entity;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductPictureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductPictureRepository::class)]
#[APIResource(
    normalizationContext: ['groups'=>['productPicture']],
    collectionOperations:['get', 'post'],
    itemOperations:['get']
    )]
class ProductPicture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["productPicture"])]
    private $id;

    ##########

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups("product", "productPicture")]
    #[Assert\NotBlank(
        message: 'Merci de rentrer une valeur'
    ),
    Assert\NotNull(
        message: 'Merci de rentrer une valeur'
    )]
    private $path;

    ##########

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["productPicture"])]
    #[Assert\Length(
        min:5,
        max:30,
        minMessage: 'Le libellé ne doit faire au moins 5 caractère',
        maxMessage: 'Le libellé ne doit pas dépasser 30 caractères'
    ),
    Assert\NotBlank(
        message: 'Merci de rentrer une valeur'
    ),
    Assert\NotNull(
        message: 'Votre champ est vide'
    )]
    private $libelle;

    ##########

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'productPictures')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["productPicture"])]
    private $product;

    ##########

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

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
