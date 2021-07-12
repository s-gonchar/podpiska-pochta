<?php


namespace Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="magazines",
 *     options={"collate":"utf8_general_ci", "charset":"utf8", "engine":"MyISAM"}
 * )
 */
class Magazine
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
    private $title;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $annotation;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $publisherName;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $publicationCode;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $quality;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $publisherLegalAddress;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $ageCategory;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $massMediaRegNum;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $massMediaRegDate;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $image;

    /**
     * @var array
     * @ORM\Column(type="json")
     */
    private $data;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="Theme", inversedBy="magazines")
     * @ORM\JoinTable(name="magazines_themes")
     */
    private $themes;
    /**
     * @var float|null
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     */
    private $price;
    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pages;
    /**
     * @var float|null
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true)
     */
    private $weight;
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dtUpdated;

    public function __construct() {
        $this->themes = new ArrayCollection();
        $this->dtUpdated = new \DateTime();
    }
    /**
     * Magazine constructor.
     * @param array $data
     * @return Magazine
     */
    public static function create($data): self
    {
        $self = new self();
        $self->title = $data['title'] ?? null;
        $self->annotation = $data['annotation'] ?? null;
        $self->publisherName = $data['publisherName'] ?? null;
        $self->publicationCode = $data['publicationCode'] ?? null;
        $self->quality = $data['quality'] ?? null;
        $self->publisherLegalAddress = $data['publisherLegalAddress'] ?? null;
        $self->ageCategory = $data['ageCategory'] ?? null;
        $self->massMediaRegNum = $data['massMediaRegNum'] ?? null;
        $self->massMediaRegDate = $data['massMediaRegDate'] ?? null;
        $self->image = $data['cover']['url'] ?? null;
        if (isset($data['tcfpsOptions'])) {
            $self->price = end($data['tcfpsOptions'])['priceFrom'] ?? null;
        }
        if (isset($data['tcfpsParts'])) {
            $self->pages = end($data['tcfpsParts'])['pagesMax'] ?? null;
            $self->weight = end($data['tcfpsParts'])['weightMax'] ?? null;
        }

        $self->data = json_encode($data, JSON_UNESCAPED_UNICODE);

        return $self;
    }


    /**
     * Magazine constructor.
     * @param array $data
     * @return Magazine
     */
    public function edit($data): self
    {
        $this->title = $data['title'] ?? null;
        $this->annotation = $data['annotation'] ?? null;
        $this->publisherName = $data['publisherName'] ?? null;
        $this->publicationCode = $data['publicationCode'] ?? null;
        $this->quality = $data['quality'] ?? null;
        $this->publisherLegalAddress = $data['publisherLegalAddress'] ?? null;
        $this->ageCategory = $data['ageCategory'] ?? null;
        $this->massMediaRegNum = $data['massMediaRegNum'] ?? null;
        $this->massMediaRegDate = $data['massMediaRegDate'] ?? null;
        $this->image = $data['cover']['url'] ?? null;
        if (isset($data['tcfpsOptions'])) {
            $this->price = end($data['tcfpsOptions'])['priceFrom'] ?? null;
        }
        if (isset($data['tcfpsParts'])) {
            $this->pages = end($data['tcfpsParts'])['pagesMax'] ?? null;
            $this->weight = end($data['tcfpsParts'])['weightMax'] ?? null;
        }

        $this->data = json_encode($data, JSON_UNESCAPED_UNICODE);
        $this->dtUpdated = new \DateTime();

        return $this;
    }

    /**
     * @return Collection
     */
    public function getThemes(): Collection
    {
        return $this->themes;
    }

    /**
     * @return string
     */
    public function getPublicationCode(): string
    {
        return $this->publicationCode;
    }
}