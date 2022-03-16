<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class NewOrderDTO
{
    #[Assert\NotBlank]
    private string $expectedTimeOfDelivery;

    #[Assert\NotBlank]
    private string $deliveryAddress;

    #[Assert\NotBlank]
    private string $billingAddress;

    #[Assert\NotBlank]
    private int $customerId;

    #[Assert\NotBlank]
    private iterable $items;

    /**
     * @return string
     */
    public function getExpectedTimeOfDelivery(): string
    {
        return $this->expectedTimeOfDelivery;
    }

    /**
     * @param string $expectedTimeOfDelivery
     */
    public function setExpectedTimeOfDelivery(string $expectedTimeOfDelivery): void
    {
        $this->expectedTimeOfDelivery = $expectedTimeOfDelivery;
    }

    /**
     * @return string
     */
    public function getDeliveryAddress(): string
    {
        return $this->deliveryAddress;
    }

    /**
     * @param string $deliveryAddress
     */
    public function setDeliveryAddress(string $deliveryAddress): void
    {
        $this->deliveryAddress = $deliveryAddress;
    }

    /**
     * @return string
     */
    public function getBillingAddress(): string
    {
        return $this->billingAddress;
    }

    /**
     * @param string $billingAddress
     */
    public function setBillingAddress(string $billingAddress): void
    {
        $this->billingAddress = $billingAddress;
    }

    /**
     * @return int
     */
    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    /**
     * @param int $customerId
     */
    public function setCustomerId(int $customerId): void
    {
        $this->customerId = $customerId;
    }

    /**
     * @return iterable
     */
    public function getItems(): iterable
    {
        return $this->items;
    }

    /**
     * @param iterable $items
     */
    public function setItems(iterable $items): void
    {
        $this->items = $items;
    }

}
