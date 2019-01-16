<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @UniqueEntity(fields={"email"}, message="Cet email est déjà utilisé.")
 * @ORM\HasLifecycleCallbacks()
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
     * @var string
     * @ORM\Column(type="string", name="firstname")
     * @Assert\NotNull(message="form.notNull")
     * @Assert\Length(min="1", max="255", minMessage="form.length.min", maxMessage="form.length.max")
     */
    private $firstname;

    /**
     * @var string
     * @ORM\Column(type="string", name="lastname")
     * @Assert\NotNull(message="form.notNull")
     * @Assert\Length(min="1", max="255", minMessage="form.length.min", maxMessage="form.length.max")
     */
    private  $lastname;

    /**
     * @var string
     * @ORM\Column(type="string", name="city")
     * @Assert\NotNull(message="form.notNull")
     * @Assert\Length(min="1", max="255", minMessage="form.length.min", maxMessage="form.length.max")
     */
    private $city;

    /**
     * @var string
     * @ORM\Column(type="string", name="country")
     * @Assert\NotNull(message="form.notNull")
     * @Assert\Length(min="1", max="255", minMessage="form.length.min", maxMessage="form.length.max")
     */
    private $country;

    /**
     * @var string
     * @ORM\Column(type="datetime", name="born_date")
     */
    private $bornDate;

    /**
     * @var string
     * @ORM\Column(type="string", name="phone_number")
     * @Assert\NotNull(message="form.notNull")
     * @Assert\Length(min="10", max="10", minMessage="form.length.min", maxMessage="form.length.max")
     */
    private $phoneNumber;

    /**
     * @var string
     * @ORM\Column(type="string", name="gender", length=10)
     */
    private $gender;

    /**
     * @ORM\OneToOne(targetEntity="Medium")
     */
    private $profilePicture;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email(strict="true", checkMX=true, message="form.email")
     * @Assert\NotNull(message="form.notNull")
     * @Assert\Length(min="1", max="255", minMessage="form.length.min", maxMessage="form.length.max")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", name="created_at")
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", name="updated_at")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Response", mappedBy="USER")
     */
    private $responses;

    /**
     * @var string
     * @ORM\Column(type="string", name="token", nullable=true)
     */
    private $token;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", name="token_request_at", nullable=true)
     */
    private $tokenRequestAt;

    public function __construct()
    {
        $this->responses = new ArrayCollection();
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
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return  $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return string
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     * @return User
     */
    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     * @return User
     */
    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return User
     */
    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return User
     */
    public function setCountry(string $country): self
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return \DateTime|string|null
     */
    public function getBornDate()
    {
        return $this->bornDate;
    }

    /**
     * @param \DateTime $bornDate
     * @return User
     */
    public function setBornDate(\DateTime $bornDate): User
    {
        $this->bornDate = $bornDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     * @return User
     */
    public function setPhoneNumber(string $phoneNumber): User
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     * @return User
     */
    public function setGender(string $gender): User
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return string
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    /**
     * @param string $profilePicture
     * @return User
     */
    public function setProfilePicture($profilePicture): User
    {
        $this->profilePicture = $profilePicture;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return User
     */
    public function setCreatedAt(\DateTime $createdAt): User
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setCreatedAtValue() : self
    {
        $this->createdAt = new \DateTime();
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return User
     */
    public function setUpdatedAt(\DateTime $updatedAt): User
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setUpdatedAtValue() : self
    {
        $this->updatedAt = new \DateTime();
        return $this;
    }

    /**
     * @return Collection|Response[]
     */
    public function getResponses(): Collection
    {
        return $this->responses;
    }

    public function addResponse(Response $response): self
    {
        if (!$this->responses->contains($response)) {
            $this->responses[] = $response;
            $response->setUSER($this);
        }

        return $this;
    }

    public function removeResponse(Response $response): self
    {
        if ($this->responses->contains($response)) {
            $this->responses->removeElement($response);
            // set the owning side to null (unless already changed)
            if ($response->getUSER() === $this) {
                $response->setUSER(null);
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return User
     */
    public function setToken(string $token): User
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTokenRequestAt(): \DateTime
    {
        return $this->tokenRequestAt;
    }

    /**
     * @param \DateTime $tokenRequestAt
     * @return User
     */
    public function setTokenRequestAt(\DateTime $tokenRequestAt): User
    {
        $this->tokenRequestAt = $tokenRequestAt;
        return $this;
    }
}
