<?php

namespace App\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\TotalRecurrenceUserController;
use App\Controller\TotalUserController;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[APIResource(
    normalizationContext: ['groups' => ['user']],
    collectionOperations: [
        'get',
        'post',
        'CountNewUser' => [
            'method' => 'GET',
            'path' => 'users/total_new_users',
            'controller' => TotalUserController::class
        ],
        'CountUserRecurrence' => [
            'method' => 'GET',
            'path' => 'users/count_user_recurrence',
            'controller' => TotalRecurrenceUserController::class
        ]
    ],
    itemOperations: ['get']
)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["adress", "command", "review", "user"])]
    private $id;

    ##########

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(["adress", "command", "review", "user"])]
    #[
        Assert\Email(
            message: 'Merci de rentrer un email valide'
        ),
        Assert\NotNull(
            message: "Merci de rentrer une valeur."
        ),
        Assert\NotBlank(
            message: 'Votre champ est vide.'
        )
    ]
    private $email;

    ###########

    #[ORM\Column(type: 'json')]
    #[Groups(["user"])]
    private $roles = [];

    ##########

    #[ORM\Column(type: 'string')]
    #[Assert\Length(
        min: 2,
        max: 15,
        minMessage: 'Votre mot de passe est trop court',
        maxMessage: 'Votre mot de passe est trop long'
    )]
    private $password;

    ##########

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["user"])]
    #[
        Assert\Type(
            type: 'string',
            message: 'Merci de rentrer une chaine de caractère'
        ),
        Assert\NotNull(
            message: "Merci de rentrer une valeur."
        ),
        Assert\NotBlank(
            message: 'Votre champ est vide.'
        )
    ]
    private $firstName;

    ##########

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["user"])]
    #[
        Assert\Type(
            type: 'string',
            message: 'Merci de rentrer une chaine de caractère'
        ),
        Assert\NotNull(
            message: "Merci de rentrer une valeur."
        ),
        Assert\NotBlank(
            message: 'Votre champ est vide.'
        )
    ]
    private $lastName;

    ##########

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Command::class)]
    #[Groups(["user"])]
    private $commands;

    ##########

    #[ORM\ManyToMany(targetEntity: Adress::class, inversedBy: 'users')]
    #[Groups(["user"])]
    private $addresses;

    ##########

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Review::class)]
    #[Groups(["user"])]
    private $Review;

    ##########

    #[ORM\Column(type: 'datetime')]
    #[Groups(["user"])]
    // #[
    //     Assert\Type(
    //         type: 'datetime',
    //         message: 'Merci de rentrer un format de date valide'
    //     ),
    //     Assert\GreaterThan(
    //         value: 'today',
    //         message: 'Merci de rentrer une date valide.'
    //     ),
    //     Assert\NotNull(
    //         message: "Merci de rentrer une valeur."
    //     ),
    //     Assert\NotBlank(
    //         message: 'Votre champ est vide.'
    //     )
    // ]
    private $createdAt;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ResetPassword::class)]
    private $resetPasswords;

    ##########

    public function __construct()
    {
        $this->commands = new ArrayCollection();
        $this->addresses = new ArrayCollection();
        $this->Review = new ArrayCollection();
        $this->resetPasswords = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getUsername()
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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
            $command->setUser($this);
        }

        return $this;
    }

    public function removeCommand(Command $command): self
    {
        if ($this->commands->removeElement($command)) {
            // set the owning side to null (unless already changed)
            if ($command->getUser() === $this) {
                $command->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Adress>
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Adress $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses[] = $address;
        }

        return $this;
    }

    public function removeAddress(Adress $address): self
    {
        $this->addresses->removeElement($address);

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReview(): Collection
    {
        return $this->Review;
    }

    public function addReview(Review $review): self
    {
        if (!$this->Review->contains($review)) {
            $this->Review[] = $review;
            $review->setUser($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->Review->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getUser() === $this) {
                $review->setUser(null);
            }
        }

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

    /**
     * @return Collection<int, ResetPassword>
     */
    public function getResetPasswords(): Collection
    {
        return $this->resetPasswords;
    }

    public function addResetPassword(ResetPassword $resetPassword): self
    {
        if (!$this->resetPasswords->contains($resetPassword)) {
            $this->resetPasswords[] = $resetPassword;
            $resetPassword->setUser($this);
        }

        return $this;
    }

    public function removeResetPassword(ResetPassword $resetPassword): self
    {
        if ($this->resetPasswords->removeElement($resetPassword)) {
            // set the owning side to null (unless already changed)
            if ($resetPassword->getUser() === $this) {
                $resetPassword->setUser(null);
            }
        }

        return $this;
    }
}
