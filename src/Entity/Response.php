<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ResponseRepository")
 */
class Response
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="responses")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Request", inversedBy="responses")
     */
    private $Request;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->Request = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getUSER(): ?User
    {
        return $this->user;
    }

    public function setUSER(?User $USER): self
    {
        $this->user = $USER;

        return $this;
    }

    /**
     * @return Collection|Request[]
     */
    public function getRequest(): Collection
    {
        return $this->Request;
    }

    public function addRequest(Request $request): self
    {
        if (!$this->Request->contains($request)) {
            $this->Request[] = $request;
        }

        return $this;
    }

    public function removeRequest(Request $request): self
    {
        if ($this->Request->contains($request)) {
            $this->Request->removeElement($request);
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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
