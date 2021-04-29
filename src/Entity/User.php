<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *  fields={"email"},
 *  message="Adresse email déjà utilisé"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Merci d'indiquer votre prénom")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Merci d'indiquer votre nom")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="Email invalide")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/", message="Le mot de passe doit comporter au moins 6 caractères et inclure au moins une lettre majuscule et un chiffre."
     * )
     */
    private $hash;

    /**
     * @Assert\EqualTo(propertyPath="hash", message="Les mots de passe ne sont pas identique")
     */
    public $passwordConfirm;

    /**
     * @ORM\OneToMany(targetEntity=Address::class, mappedBy="user")
     */
    private $addresses;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="user")
     */
    private $orders;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class, mappedBy="users")
     */
    private $userRoles;

    /**
     * @ORM\OneToMany(targetEntity=Documents::class, mappedBy="user")
     */
    private $documents;

    // /**
    //  * @ORM\OneToMany(targetEntity=Folders::class, mappedBy="user")
    //  */
    // private $folders;

    public function __construct()
    {
        $this->uploads = new ArrayCollection();
        $this->addresses = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
        $this->documents = new ArrayCollection();
        // $this->folders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getRoles()
    {
        $roles = $this->userRoles->map(function ($role) {
            return $role->getTitle();
        })->toArray();

        $roles[] = 'ROLE_USER';

        return $roles;
    }

    public function getPassword()
    {
        return $this->hash;
    }

    public function getSalt()
    {
        //
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
        //
    }

    /**
     * @return Collection|Address[]
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses[] = $address;
            $address->setUser($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->addresses->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getUser() === $this) {
                $address->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setUser($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getUser() === $this) {
                $order->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Role[]
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(Role $userRole): self
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles[] = $userRole;
            $userRole->addUser($this);
        }

        return $this;
    }

    public function removeUserRole(Role $userRole): self
    {
        if ($this->userRoles->removeElement($userRole)) {
            $userRole->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Documents[]
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Documents $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setUser($this);
        }

        return $this;
    }

    public function removeDocument(Documents $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getUser() === $this) {
                $document->setUser(null);
            }
        }

        return $this;
    }

    // /**
    //  * @return Collection|Folders[]
    //  */
    // public function getFolders(): Collection
    // {
    //     return $this->folders;
    // }

    // public function addFolder(Folders $folder): self
    // {
    //     if (!$this->folders->contains($folder)) {
    //         $this->folders[] = $folder;
    //         $folder->setUser($this);
    //     }

    //     return $this;
    // }

    // public function removeFolder(Folders $folder): self
    // {
    //     if ($this->folders->removeElement($folder)) {
    //         // set the owning side to null (unless already changed)
    //         if ($folder->getUser() === $this) {
    //             $folder->setUser(null);
    //         }
    //     }

    //     return $this;
    // }
}
