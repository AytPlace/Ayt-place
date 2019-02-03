<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints\availableDate as Interval;
use App\Validator\Constraints\dateInterval as Date;
/**
 * @ORM\Entity(repositoryClass="App\Repository\OfferRepository")
 */
class Offer
{
    const REGION = [
        'Séléctionner une région' => '',
        'Rhône-Alpes' =>  0,
        'Alsace' =>  1,
        'Aquitaine' =>  2,
        'Auvergne' =>  3,
        'Basse-Normandie' =>  4,
        'Bourgogne' =>  5,
        'Bretagne' =>  6,
        'Centre' =>  7,
        'Champagne' =>  8,
        'Corse' =>  9,
        'Franche-Comté' =>  10,
        'Haute-Normandie' =>  11,
        'Ile-de-France' =>  12,
        'Languedoc' =>  13,
        'Limousin' =>  14,
        'Lorraine' =>  15,
        'Midi-Pyrénées' =>  16,
        'Nord' =>  17,
        'Normandie' =>  18,
        'Pays-de-la-Loire' =>  19,
        'Picardie' =>  20,
        'Poitou-Charente' =>  21,
        'Provence-Alpes-Côte d\'Azur' =>  22,
    ];
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
     * @ORM\ManyToMany(targetEntity="Request", inversedBy="offer")
     * @ORM\JoinTable(name="offers_requests")
     */
    private $requests;

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
     * @ORM\Column(type="text", nullable=false)
     * @Assert\NotNull(message="form.notNull")
     */
    private $region;

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
     * @Date\ContainsDateInterval
     * @Interval\ContainsAvailableDate
     */
    protected $availabilityOffers;

    public function __construct()
    {
        $this->availabilityOffers = new ArrayCollection();
        $this->requests = new ArrayCollection();
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

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->requests;
    }

    public function addRequest(Request $request) :self
    {
        if (!$this->requests->contains($request)) {
            $this->requests->add($request);
        }

        return $this;
    }

    public function removeRequest(Request $request) :self
    {
        if ($this->requests->contains($request)) {
            $this->requests->removeElement($request);
        }

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

    /**
     * @return mixed
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param mixed $region
     */
    public function setRegion($region): void
    {
        $this->region = $region;
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

    public function getRegionName( string $key) : string
    {
        return array_search($key, self::REGION);
    }

}
