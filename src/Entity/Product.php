<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[APIResource( 
    normalizationContext: ['groups'=>['product']],
    collectionOperations:['get', 'post'],
    itemOperations:['get']
    )]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(["category", "brand", "command", "productPicture"])]
    #[ORM\Column(type: 'integer')]
    private $id;

    ##########

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["category", "brand", "command", "product", "productPicture"])]
    #[Assert\Length(
        min:2,
        max:15,
        minMessage: 'Votre label est trop court',
        maxMessage: 'Votre label est trop long'
    ),
    Assert\NotBlank(
        message: 'Votre champ est vide'
    ),
    Assert\NotNull(
        message: 'Merci de rentrer une valeur'
    )]
    private $label;

    ##########

    #[ORM\Column(type: 'text')]
    #[Groups(["product"])]
    #[Assert\Length(
        min:5,
        max:350,
        minMessage: 'La description ne doit faire au moins 5 caractère',
        maxMessage: 'La description ne doit pas dépasser 350 caractères'
    ),
    Assert\NotBlank(
        message: 'Votre champ est vide'
    ),
    Assert\NotNull(
        message: 'Merci de rentrer une valeur'
    )]
    private $description;

    ##########

    #[ORM\Column(type: 'boolean')]
    #[Groups(["product"])]
    #[Assert\Type(
        type: 'boolean',
        message: 'Merci de rentrer un boolen'
    )]
    private $isActif;

    ##########

    #[ORM\Column(type: 'integer')]
    #[Groups(["product"])]
    #[Assert\PositiveOrZero(
        message: "Le stock ne peut pas être négatif"
    ),
    Assert\Type(
        type: 'integer',
        message: 'Merci de rentrer un nombre'
    )]
    private $stock;

    ##########
    
    #[ORM\Column(type: 'integer')]
    #[Groups(["product", "category"])]
    #[Assert\NotNull(
        message: 'Merci de rentrer une valeur'
    ),
    Assert\PositiveOrZero(
        message: "Le prix ne peut pas être négatif"
    ), 
    Assert\Type(
        type: 'int',
        message: 'Merci de rentrer un nombre'
    )]
    private $price;

    ##########

    #[ORM\ManyToMany(targetEntity: Command::class, mappedBy: 'products')]
    #[Groups(["product"])]
    private $commands;

    ##########

    #[ORM\ManyToOne(targetEntity: Brand::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["product"])]
    private $brand;

    ##########

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Review::class)]
    #[Groups(["product"])]
    private $reviews;

    ##########

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'products')]
    #[Groups(["product"])]
    private $categories;

    ##########

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductPicture::class, cascade:['persist', 'remove'])]
    #[Groups(["product"])]
    private $productPictures;

    ##########

    public function __construct()
    {
        $this->commands = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->productPictures = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIsActif(): ?bool
    {
        return $this->isActif;
    }

    public function setIsActif(bool $isActif): self
    {
        $this->isActif = $isActif;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, Command>
     */
    public function getCommands(): Collection
    {
        return $this->commands;
    }

    public function addCommand(Command $command): self
    {
        if (!$this->commands->contains($command)) {
            $this->commands[] = $command;
            $command->addProduct($this);
        }

        return $this;
    }

    public function removeCommand(Command $command): self
    {
        if ($this->commands->removeElement($command)) {
            $command->removeProduct($this);
        }

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setProduct($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getProduct() === $this) {
                $review->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, ProductPicture>
     */
    public function getProductPictures(): Collection
    {
        return $this->productPictures;
    }

    public function addProductPicture(ProductPicture $productPicture): self
    {
        if (!$this->productPictures->contains($productPicture)) {
            $this->productPictures[] = $productPicture;
            $productPicture->setProduct($this);
        }

        return $this;
    }

    public function removeProductPicture(ProductPicture $productPicture): self
    {
        if ($this->productPictures->removeElement($productPicture)) {
            // set the owning side to null (unless already changed)
            if ($productPicture->getProduct() === $this) {
                $productPicture->setProduct(null);
            }
        }

        return $this;
    }
}
