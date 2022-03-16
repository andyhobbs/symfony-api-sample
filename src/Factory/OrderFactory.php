<?php

declare(strict_types=1);

namespace App\Factory;

use App\DBAL\StatusTypes;
use App\DTO\NewOrderDTO;
use App\Entity\Order;
use App\Entity\OrderItem;

class OrderFactory
{
    public static function createFromDto(NewOrderDTO $dto): Order
    {
        $order = new Order();
        $order->setCustomerId($dto->getCustomerId());
        $order->setExpectedTimeOfDelivery(new \DateTime($dto->getExpectedTimeOfDelivery()));
        $order->setDeliveryAddress($dto->getDeliveryAddress());
        $order->setBillingAddress($dto->getBillingAddress());
        $order->setStatus(StatusTypes::NEW);

        foreach ($dto->getItems() as $item) {
            $orderItem = new OrderItem();
            $orderItem->setQuantity($item['quantity']);
            $order->addItem($orderItem);
        }

        return $order;
    }
}
