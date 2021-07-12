<?php

namespace Repositories;

use Doctrine\ORM\EntityManagerInterface;
use Entities\Magazine;
use Entities\Theme;

class MagazineRepository extends AbstractRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
        $this->repo = $entityManager->getRepository(Magazine::class);
    }

    public function findOneByPublicationCode($code): ?Magazine
    {
        /** @var Magazine|null $magazine */
        $magazine = $this->repo->findOneBy([
            'publicationCode' => $code,
        ]);
        return $magazine;
    }
}