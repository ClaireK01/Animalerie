<?php

namespace App\Entity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[APIResource(
    normalizationContext: ["groups" => ["category"] ],
    collectionOperations:['get', 'post'],
    itemOperations:['get']
    )]
class Category
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(["category"])]
    #[ORM\Column(type: 'integer')]
    private $id;

    ##########

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["category", "product"])]
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
    )]
    private $label;

    ##########

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'categories')]
    private $categoryParent;

    ##########

    #[ORM\OneToMany(mappedBy: 'categoryParent', targetEntity: self::class)]
    private $categories;

    ##########

    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'categories')]
    #[Groups(["category"])]
    private $products;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
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

    public function getCategoryParent(): ?self
    {
        return $this->categoryParent;
    }

    public function setCategoryParent(?self $categoryParent): self
    {
        $this->categoryParent = $categoryParent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(self $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setCategoryParent($this);
        }

        return $this;
    }

    public function removeCategory(self $category): self
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getCategoryParent() === $this) {
                $category->setCategoryParent(null);
            }
        }

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
            $product->addCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            $product->removeCategory($this);
        }

        return $this;
    }

   
}
