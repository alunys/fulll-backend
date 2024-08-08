<?php

declare(strict_types=1);

namespace Fulll\Infra\Repository\Doctrine\ORM;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
use Fulll\Domain\Model\Vehicle;
use Fulll\Domain\Repository\VehicleRepositoryInterface;

readonly class VehicleRepository implements VehicleRepositoryInterface
{
    private ObjectRepository $repository;

    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Vehicle::class);
    }

    public function getByPlateNumber(string $plateNumber): ?Vehicle
    {
        return $this->repository->findOneBy(['plateNumber' => $plateNumber]);
    }

    public function save(Vehicle $vehicle): void
    {
        $this->entityManager->persist($vehicle);
        $this->entityManager->flush();
    }
}
