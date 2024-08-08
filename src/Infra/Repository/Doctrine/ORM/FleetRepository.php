<?php

declare(strict_types=1);

namespace Fulll\Infra\Repository\Doctrine\ORM;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Fulll\Domain\Model\Fleet;
use Fulll\Domain\Repository\FleetRepositoryInterface;

readonly class FleetRepository implements FleetRepositoryInterface
{
    /** @var ObjectRepository<Fleet> */
    private ObjectRepository $repository;

    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Fleet::class);
    }

    public function getById(string $id): ?Fleet
    {
        return $this->repository->find($id);
    }

    public function getByUserId(string $userId): ?Fleet
    {
        return $this->repository->findOneBy(['userId' => $userId]);
    }

    public function save(Fleet $fleet): void
    {
        // Check if another fleet is already assigned to the user of this fleet
        foreach ($this->repository->findAll() as $existingFleet) {
            if ($existingFleet->id === $fleet->id) {
                continue;
            }

            if ($existingFleet->userId === $fleet->userId) {
                throw new \RuntimeException('Fleet already exists for this user');
            }
        }

        $this->entityManager->persist($fleet);
        $this->entityManager->flush();
    }
}
