<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: 'orders')]
class Order implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'integer')]
    private int $customerId;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $expectedTimeOfDelivery;

    #[ORM\Column(type: 'string', length: 255)]
    private string $billingAddress;

    #[ORM\Column(type: 'string', length: 255)]
    private string $deliveryAddress;

    #[ORM\Column(type: 'string')]
    private string $status;

    #[ORM\OneToMany(mappedBy: "post", targetEntity: OrderItem::class, cascade: ['persist', 'merge', "remove"], fetch: 'LAZY', orphanRemoval: true)]
    private Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomerId(): ?int
    {
        return $this->customerId;
    }

    public function setCustomerId(int $customerId): self
    {
        $this->customerId = $customerId;

        return $this;
    }

    public function getExpectedTimeOfDelivery(): ?\DateTimeInterface
    {
        return $this->expectedTimeOfDelivery;
    }

    public function setExpectedTimeOfDelivery(\DateTimeInterface $expectedTimeOfDelivery): self
    {
        $this->expectedTimeOfDelivery = $expectedTimeOfDelivery;

        return $this;
    }

    public function getBillingAddress(): ?string
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(string $billingAddress): self
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    public function getDeliveryAddress(): ?string
    {
        return $this->deliveryAddress;
    }

    public function setDeliveryAddress(string $deliveryAddress): self
    {
        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(OrderItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setOrder($this);
        }

        return $this;
    }

    public function removeItem(OrderItem $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getOrder() === $this) {
                $item->setOrder(null);
            }
        }

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'customerId' => $this->getCustomerId(),
            'expectedTimeOfDelivery' => $this->getExpectedTimeOfDelivery()->format('Y-m-d')
        ];
    }
}
