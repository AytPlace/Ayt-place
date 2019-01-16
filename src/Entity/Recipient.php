<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Recipient extends User
{
    /**
     * @ORM\Column(type="string", length=9)
     */
    private $siren;

    /**
     * @ORM\OneToOne(targetEntity="Medium")
     */
    private $sirenPicture;

    /**
     * @ORM\OneToOne(targetEntity="Medium")
     */
    private $identityCardPicture;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Offer", mappedBy="recipient")
     */
    private $offers;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Status", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="status", referencedColumnName="id")
     */
    private $status;


    public function getSiren(): ?string
    {
        return $this->siren;
    }

    public function setSiren(string $siren): self
    {
        $this->siren = $siren;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSirenPicture()
    {
        return $this->sirenPicture;
    }

    /**
     * @param mixed $sirenPicture
     */
    public function setSirenPicture($sirenPicture): void
    {
        $this->sirenPicture = $sirenPicture;
    }

    /**
     * @return mixed
     */
    public function getIdentityCardPicture()
    {
        return $this->identityCardPicture;
    }

    /**
     * @param mixed $identityCardPicture
     */
    public function setIdentityCardPicture($identityCardPicture): void
    {
        $this->identityCardPicture = $identityCardPicture;
    }

    /**
     * @return Collection|Offer[]
     */
    public function getOffers(): ?Offer
    {
        return $this->offers;
    }

    public function setOffers(Offer $offer) : self
    {
        $this->offers = $offer;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): self
    {
        $this->status = $status;

        return $this;
    }
}
