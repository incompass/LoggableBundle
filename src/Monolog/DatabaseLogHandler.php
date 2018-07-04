<?php declare(strict_types=1);

namespace Incompass\LoggableBundle\Monolog;

use Doctrine\ORM\EntityManagerInterface;
use Incompass\LoggableBundle\Entity\Log;
use Monolog\Handler\AbstractProcessingHandler;

/**
 * Class DatabaseLogHandler
 *
 * @package Incompass\LoggableBundle\Monolog
 * @author  Mike Bates <mike@casechek.com>
 * @author  Joe Mizzi <joe@casechek.com>
 * @author  James Matsumura <james@casechek.com>
 */
class DatabaseLogHandler extends AbstractProcessingHandler
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * DatabaseLogHandler constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    /**
     * @param array $record
     */
    protected function write(array $record)
    {
        $logEntry = new Log();
        $logEntry->setMessage($record['message']);
        $logEntry->setLevel($record['level']);
        $logEntry->setLevelName($record['level_name']);
        $logEntry->setExtra($record['extra']);
        $logEntry->setContext($record['context']);

        $this->em->persist($logEntry);
        $this->em->flush();
    }
}