<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class AnnonceSearch
{

    /**
     * @var string|null
     */
    private $title;

    /**
     * @var int|null
     */
    private $status;

    /**
     * @var int|null
     */
    private $maxPrice;
    
    /**
     * @var \DateTimeInterface|null
     */
    private $createdAt;

    /**
     * @var ArrayCollection
     */
    private $tags;
   
    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title = null): self
    {
        $this->title = $title;

        return $this;
    }

    public function getMaxPrice(): ?int
    {
        return $this->maxPrice;
    }

    public function setMaxPrice(?int $maxPrice = null)
    {
        $this->maxPrice = $maxPrice;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status = null): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt = null): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getTags(): ?ArrayCollection
    {
        return $this->tags;
    }

    public function setTags(?ArrayCollection $tags)
    {
        $this->tags = $tags;
        return $this;
    }
}