<?php


namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="logs",
 *     options={"collate":"utf8_general_ci", "charset":"utf8", "engine":"MyISAM"}
 * )
 */
class Log
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $dt;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $success;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $error;

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function __construct($error = null)
    {
        $this->dt = new \DateTime();
        $this->success = !$error;
        $this->error = $error;
    }
}