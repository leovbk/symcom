<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *  fields = {"email"},
 *  message = "Cet email est déjà utilisé"
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
     * @Assert\NotBlank(
     *  message="Veuillez renseigner une adresse e-mail",
     *  groups={"registration"}
     * )
     * @Assert\Email(
     *  message="Veuillez saisir une adresse email valide",
     *  groups={"registration"}
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\NotBlank(
     *  message="Veuillez renseigner ce champ",
     *  groups={"registration"}
     *  )
     * @Assert\Length(
     *  min="2", max="45",
     *  minMessage="Votre prénom doit comporter aux moins 4 caractères",
     *  maxMessage="Votre prénom doit comporter maximum 45 caractères",
     *  groups={"registration"}
     * )
     * 
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *  message="Veuillez renseigner ce champ",
     *  groups={"registration"}
     *  )
     * @Assert\Length(
     *  min="2", max="45",
     *  minMessage="Votre nom doit comporter aux moins 4 caractères",
     *  maxMessage="Votre nom doit comporter maximum 45 caractères",
     *  groups={"registration"}
     * )
     * 
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\EqualTo(
     *  propertyPath="confirm_password",
     *  message="Les mots de passes ne correspondent pas",
     *  groups={"registration"}
     * )
     * @Assert\NotBlank(
     *  message="Veuillez saisir un mot de passe",
     *  groups={"registration"}
     *  )
     */
    private $password;

    /**
     * @Assert\EqualTo(
     *  propertyPath="password",
     *  message="Les mots de passes ne correspondent pas",
     *  groups={"registration"}
     * )
     * @Assert\NotBlank(
     *  message="Veuillez confirmer votre mot de passe",
     *  groups={"registration"}
     *  )
     * @Assert\Length(
     *  min="6",
     *  minMessage="Votre mot de passe doit comporter aux moins 6 caractères",
     *  groups={"registration"}
     * )
     */
    public $confirm_password;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getSalt(){}

    public function eraseCredentials(){}

    /**
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    
}