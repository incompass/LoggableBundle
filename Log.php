<?php

namespace Incompass\LoggableBundle;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Log
 *
 * @package Incompass\LoggableBundle
 * @author  Joe Mizzi <joe@casechek.com>
 * @author  Mike Bates <mike@casechek.com>
 *
 * @ORM\Entity()
 */
class Log
{
    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer|null
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=7)
     * @var string
     */
    private $action;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    private $loggedAt;

    /**
     * @ORM\Column(type="integer", options={"unsigned"=true}, nullable=true)
     * @var integer|null
     */
    private $loggedById;

    /**
     * @ORM\Column(type="integer", options={"unsigned"=true}, nullable=true)
     * @var integer|null
     */
    private $objectId;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $objectClass;

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    private $data;

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return DateTime
     */
    public function getLoggedAt()
    {
        return $this->loggedAt;
    }

    /**
     * @param DateTime $loggedAt
     */
    public function setLoggedAt(DateTime $loggedAt)
    {
        $this->loggedAt = $loggedAt;
    }

    /**
     * @return int|null
     */
    public function getLoggedById()
    {
        return $this->loggedById;
    }

    /**
     * @param int|null $loggedById
     */
    public function setLoggedById($loggedById)
    {
        $this->loggedById = $loggedById;
    }

    /**
     * @return int|null
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * @param int|null $objectId
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;
    }

    /**
     * @return string
     */
    public function getObjectClass()
    {
        return $this->objectClass;
    }

    /**
     * @param string $objectClass
     */
    public function setObjectClass($objectClass)
    {
        $this->objectClass = $objectClass;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
}
