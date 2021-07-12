<?php

namespace Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="themes",
 *     options={"collate":"utf8_general_ci", "charset":"utf8", "engine":"MyISAM"}
 * )
 */
class Theme
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var int
     * @ORM\Column(name="external_id", type="integer")
     */
    private $externalId;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="Magazine", mappedBy="themes")
     */
    private $magazines;

    public function __construct() {
        $this->magazines = new ArrayCollection();
    }

    public static function create(string $name, string $externalId): self
    {
        $self = new self();
        $self->externalId = $externalId;
        $self->name = $name;

        return $self;
    }

    /**
     * @return int
     */
    public function getExternalId(): int
    {
        return $this->externalId;
    }
}