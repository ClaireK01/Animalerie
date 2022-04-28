<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BrandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BrandRepository::class)]
#[ApiResource(
    normalizationContext: ['groups'=>['brand']],
    collectionOperations:['get', 'post'],
    itemOperations:['get']
    )]
class Brand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups('brand', "product")]
    private $id;

    ##########

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups('brand', "product")]
    #[Assert\Length(
        min:2,
        max:50,
        minMessage: 'Votre label est trop court',
        maxMessage: 'Votre label est trop long'
    ), 
    Assert\NotNull(
        message: 'Merci de rentrer une valeur'
    ),
    Assert\NotBlank(
        message: 'Votre champ est vide'
    ),
    Assert\Type(
        type: 'string',
        message: 'Merci de rentrer une chaine de caractère'
    )]
    private $label;

    ##########

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(
        min:2,
        max:50,
        minMessage: 'Votre chemin est trop court',
        maxMessage: 'Votre chemin est trop long'
    ), 
    Assert\NotNull(
        message: 'Merci de rentrer une valeur'
    ),
    Assert\NotBlank(
        message: 'Votre champ est vide'
    )]
    private $imagePath;

    ##########

    #[ORM\OneToMany(mappedBy: 'brand', targetEntity: Product::class)]
    #[Groups('brand')]
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(string $imagePath): self
    {
        $this->imagePath = $imagePath;

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
            $product->setBrand($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getBrand() === $this) {
                $product->setBrand(null);
            }
        }

        return $this;
    }
}
