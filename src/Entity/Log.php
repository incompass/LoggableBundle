<?php declare(strict_types=1);

namespace Incompass\LoggableBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

/**
 * Class Log
 *
 * @package Incompass\LoggableBundle\Entity
 * @author  Mike Bates <mike@casechek.com>
 * @author  Joe Mizzi <joe@casechek.com>
 * @author  James Matsumura <james@casechek.com>
 *
 * @ApiResource()
 * @Entity()
 * @Table(
 *     indexes={
 *         @Index(name="log_idx_level", columns={"case_id"}),
 *         @Index(name="log_idx_level_name", columns={"surgery_date_time"})
 *     }
 * )
 * @ORM\HasLifecycleCallbacks
 */
class Log
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    private $id;

    /**
     * @Column(name="message", type="text")
     * @var string
     */
    private $message;

    /**
     * @Column(name="context", type="json_array")
     * @var array
     */
    private $context;

    /**
     * @Column(name="level", type="smallint")
     * @var integer
     */
    private $level;

    /**
     * @Column(name="level_name", type="string", length=50)
     * @var string
     */
    private $levelName;

    /**
     * @Column(name="extra", type="json_array")
     * @var array
     */
    private $extra;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * @param array $context
     */
    public function setContext(array $context): void
    {
        $this->context = $context;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param int $level
     */
    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    /**
     * @return string
     */
    public function getLevelName(): string
    {
        return $this->levelName;
    }

    /**
     * @param string $levelName
     */
    public function setLevelName(string $levelName): void
    {
        $this->levelName = $levelName;
    }

    /**
     * @return array
     */
    public function getExtra(): array
    {
        return $this->extra;
    }

    /**
     * @param array $extra
     */
    public function setExtra(array $extra): void
    {
        $this->extra = $extra;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}