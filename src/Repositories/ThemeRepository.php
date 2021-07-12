<?php


namespace Repositories;


use Doctrine\ORM\EntityManagerInterface;
use Entities\Theme;

class ThemeRepository extends AbstractRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
        $this->repo = $entityManager->getRepository(Theme::class);
    }

    public function findOneByExternalId($id): ?Theme
    {
        /** @var Theme|null $theme */
        $theme = $this->repo->findOneBy(['externalId' => $id]);
        return $theme;
    }

    /**
     * @param $id
     * @return Theme
     * @throws \Exception
     */
    public function getByExternalId($id): Theme
    {
        $theme = $this->findOneByExternalId($id);
        if (!$theme) {
            throw new \Exception("Theme with externalId {$id} not found");
        }

        return $theme;
    }

    /**
     * @return Theme[]
     */
    public function getAll(): array
    {
        return $this->repo->findAll();
    }
}