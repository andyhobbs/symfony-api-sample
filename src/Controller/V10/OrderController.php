<?php

declare(strict_types=1);

namespace App\Controller\V10;

use App\DTO\NewOrderDTO;
use App\DTO\UpdateStatusDTO;
use App\Entity\Order;
use App\Factory\OrderFactory;
use App\Repository\DelayedOrderRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route(path: "/orders/v1/", name: "orders_")]
class OrderController extends AbstractController
{
    public function __construct(private EntityManagerInterface $objectManager,
                                private OrderRepository $orders,
                                private DelayedOrderRepository $delayedOrders,
                                private SerializerInterface $serializer,
                                private ValidatorInterface $validator
    ) {
    }

    #[Route(path: "", name: "create", methods: ["POST"])]
    public function create(Request $request): JsonResponse
    {
        $object = $this->serializer->deserialize($request->getContent(), NewOrderDTO::class, 'json');

        if ($this->validator->validate($object)->count()) {
            return $this->json('Request data error', Response::HTTP_BAD_REQUEST);
        }

        $order = OrderFactory::createFromDto($object);
        $this->orders->add($order);

        return $this->json($order->jsonSerialize(), Response::HTTP_CREATED);
    }

    #[Route(path: "{id}", name: "update_status", methods: ["PATCH"])]
    public function updateStatus(Request $request, Order $order): JsonResponse
    {
        $object = $this->serializer->deserialize($request->getContent(), UpdateStatusDTO::class, 'json');

        if ($this->validator->validate($object)->count()) {
            return $this->json('Request data error', Response::HTTP_BAD_REQUEST);
        }

        $order->setStatus($object->getStatus());
        $this->objectManager->persist($order);
        $this->objectManager->flush();

        return $this->json([], Response::HTTP_NO_CONTENT);
    }

    #[Route(path: "", name: "all", methods: ["GET"])]
    public function all(Request $request): JsonResponse
    {
        $data = $this->orders->findByStatus($request->query->get('status'));

        return $this->json($data);
    }

    #[Route(path: "{id}", name: "one", methods: ["GET"])]
    public function one(Order $order): JsonResponse
    {
        return $this->json($order->jsonSerialize());
    }

    #[Route(path: "/delayed", name: "delayed", methods: ["GET"])]
    public function delayed(Request $request): JsonResponse
    {
        $startDate = \DateTime::createFromFormat('Y-m-d', $request->query->get('start'));
        $endDate = \DateTime::createFromFormat('Y-m-d', $request->query->get('end'));

        if (!$startDate || $endDate) {
            return $this->json('Request data error', Response::HTTP_BAD_REQUEST);
        }

        $data = $this->delayedOrders->findByBetweenDates($startDate, $endDate);

        return $this->json($data);
    }
}
