<?php

namespace Incompass\LoggableBundle\Tests;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class EntityStub
 *
 * @package Incompass\LoggableBundle\Tests
 * @author  Joe Mizzi <joe@casechek.com>
 * @author  Mike Bates <mike@casechek.com>
 *
 * @ORM\Entity()
 */
class EntityStub
{
    /**
     * @var mixed
     */
    private $id;

    /**
     * @var mixed
     */
    private $name;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
