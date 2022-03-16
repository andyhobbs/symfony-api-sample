<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\DelayedOrder;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:delayed-order-process',
    description: 'Verify ETD orders',
)]
class DelayedOrderProcessCommand extends Command
{
    public function __construct(private OrderRepository $repository, private EntityManagerInterface $manager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $date = new \DateTime();

        $orders = $this->repository->findDelayed($date);

        foreach ($orders as $order) {
            $delayedOrder = new DelayedOrder();
            $delayedOrder->setOrder($order);
            $delayedOrder->setCreatedAt($date);
            $delayedOrder->setEtd($order->getExpectedTimeOfDelivery());

            $this->manager->persist($delayedOrder);
        }

        $this->manager->flush();

        return Command::SUCCESS;
    }

}
