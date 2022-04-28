<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CityRepository::class)]
#[APIResource(
    normalizationContext: ['groups'=>['city']],
    collectionOperations:['get', 'post'],
    itemOperations:['get'])]
#[ApiFilter(SearchFilter::class, properties:['name' => 'exact', 'id'=>'exact'])]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["city"])]
    private $id;

    ##########

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["adress", "city"])]
    #[Assert\Length(
        min:2,
        max:40,
        minMessage: 'Votre nom doit faire 2 caractères minimum.',
        maxMessage: 'Votre nom est trop long.'
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
    private $name;

    ##########

    #[ORM\Column(type: 'integer')]
    #[Groups(["city"])]
    #[Assert\NotNull(
        message: 'Merci de rentrer une valeur'
    ),
    Assert\NotBlank(
        message: 'Votre champ est vide'
    )]
    private $code_postal;

    ##########

    #[ORM\OneToMany(mappedBy: 'city', targetEntity: Adress::class)]
    #[Groups(["city"])]
    private $adresses;

    ##########

    public function __construct()
    {
        $this->adresses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->code_postal;
    }

    public function setCodePostal(int $code_postal): self
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    /**
     * @return Collection<int, Adress>
     */
    public function getAdresses(): Collection
    {
        return $this->adresses;
    }

    public function addAdress(Adress $adress): self
    {
        if (!$this->adresses->contains($adress)) {
            $this->adresses[] = $adress;
            $adress->setCity($this);
        }

        return $this;
    }

    public function removeAdress(Adress $adress): self
    {
        if ($this->adresses->removeElement($adress)) {
            // set the owning side to null (unless already changed)
            if ($adress->getCity() === $this) {
                $adress->setCity(null);
            }
        }

        return $this;
    }
}
