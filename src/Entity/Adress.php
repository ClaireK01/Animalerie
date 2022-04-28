<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AdressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdressRepository::class)]
#[APIResource( 
    normalizationContext: ['groups'=>'adress'],
    collectionOperations:['get', 'post'],
    itemOperations:['get']
    )]
class Adress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["adress", "city", "command", "user"])]
    private $id;

    ##########

    #[ORM\Column(type: 'integer')]
    #[Groups(["adress", "city", "command", "user"])]
    #[Assert\NotBlank(
        message: 'Merci de rentrer une valeur'
    ),
    Assert\NotNull(
        message: 'Merci de rentrer une valeur'
    ),
    Assert\Type(
        type: 'int',
        message: 'Merci de rentrer un nombre'
    )]
    private $streetNumber;

    ##########

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["adress", "city", "command", "user"])]
    #[Assert\Length(
        min:2,
        max:8,
        minMessage: 'Votre label est trop court',
        maxMessage: 'Votre label est trop long'
    ),
    Assert\NotBlank(
        message: 'Merci de rentrer une valeur'
    ),
    Assert\Type(
        type: 'string',
        message: 'Merci de rentrer une chaine de caractère'
    )]
    private $streetName;

    ##########

    #[ORM\OneToMany(mappedBy: 'adress', targetEntity: Command::class)]
    #[Groups(["adress"])]
    private $commands;

    ##########

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'addresses')]
    #[Groups(["adress"])]
    private $users;

    ##########

    #[ORM\ManyToOne(targetEntity: City::class, inversedBy: 'adresses')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["adress"])]
    private $city;

    public function __construct()
    {
        $this->commands = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStreetNumber(): ?int
    {
        return $this->streetNumber;
    }

    public function setStreetNumber(int $streetNumber): self
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    public function getStreetName(): ?string
    {
        return $this->streetName;
    }

    public function setStreetName(string $streetName): self
    {
        $this->streetName = $streetName;

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
            $command->setAdress($this);
        }

        return $this;
    }

    public function removeCommand(Command $command): self
    {
        if ($this->commands->removeElement($command)) {
            // set the owning side to null (unless already changed)
            if ($command->getAdress() === $this) {
                $command->setAdress(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addAddress($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeAddress($this);
        }

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }
}
