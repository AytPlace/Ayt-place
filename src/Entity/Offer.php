<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AcmeAssert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\OfferRepository")
 */
class Offer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Recipient", inversedBy="offers")
     * @ORM\JoinColumn(name="offer", referencedColumnName="id")
     */
    private $recipient;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="form.notNull")
     * @Assert\Length(max="255", min="1", minMessage="form.length.min", maxMessage="form.length.max")
     */
    public $title;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(message="form.notNull")
     */
    private $travelerNumbers;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(message="form.notNull")
     */
    private $costByTraveler;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="form.notNull")
     */
    private $location;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="form.notNull")
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="form.notNull")
     */
    private $country;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotNull(message="form.notNull")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AvailabilityOffer", mappedBy="offer", cascade={"persist"})
     * @AcmeAssert\ContainsAvailableDate
     */
    protected $availabilityOffers;

    public function __construct()
    {
        $this->availabilityOffers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecipient(): ?Recipient
    {
        return $this->recipient;
    }

    public function setRecipient(?Recipient $recipient): self
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTravelerNumbers(): ?int
    {
        return $this->travelerNumbers;
    }

    public function setTravelerNumbers(int $travelerNumbers): self
    {
        $this->travelerNumbers = $travelerNumbers;

        return $this;
    }

    public function getCostByTraveler(): ?int
    {
        return $this->costByTraveler;
    }

    public function setCostByTraveler(int $costByTraveler): self
    {
        $this->costByTraveler = $costByTraveler;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


    /**
     * @return Collection|AvailabilityOffer[]
     */
    public function getAvailabilityOffers(): Collection
    {
        return $this->availabilityOffers;
    }

    public function hasAvailabilityOffers(): bool
    {
        return (count($this->availabilityOffers) > 0) ? true : false;
    }

    public function addAvailabilityOffer(AvailabilityOffer $availabilityOffer): self
    {
        if (!$this->availabilityOffers->contains($availabilityOffer)) {
            $this->availabilityOffers->add($availabilityOffer);
            $availabilityOffer->setOffer($this);
        }

        return $this;
    }

    public function removeAvailabilityOffer(AvailabilityOffer $availabilityOffer): self
    {
        if ($this->availabilityOffers->contains($availabilityOffer)) {
            $this->availabilityOffers->remove($availabilityOffer);
        }

        return $this;
    }
}
