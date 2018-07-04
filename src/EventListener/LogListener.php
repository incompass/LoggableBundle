<?php

namespace Incompass\LoggableBundle\EventListener;

use DateTime;
use Doctrine\Common\EventArgs;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Exception;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class LogListener
 *
 * @package Incompass\LoggableBundle\EventListener
 * @author  Joe Mizzi <joe@casechek.com>
 * @author  Mike Bates <mike@casechek.com>
 * @author  James Matsumura <james@casechek.com>
 */
class LogListener implements EventSubscriber
{
    const ACTION_CREATED = 'created';

    const ACTION_DELETED = 'deleted';

    const ACTION_UPDATED = 'updated';

    private $pendingLogInserts = [];

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * LogListener constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'onFlush',
            'postPersist'
        ];
    }

    /**
     * @param OnFlushEventArgs $args
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $entityManager = $args->getEntityManager();
        $unitOfWork = $entityManager->getUnitOfWork();

        foreach ($unitOfWork->getScheduledEntityInsertions() as $entity) {
            $logClassMetadata = $entityManager->getClassMetadata(Log::class);
            /** @var Log $log */
            $log = $logClassMetadata->newInstance();
            $log->setAction(self::ACTION_CREATED);
            $log->setData(json_encode($unitOfWork->getEntityChangeSet($entity)));
            $log->setLoggedAt(new DateTime());
            $user = $this->tokenStorage->getToken()->getUser();
            if ($user) {
                $userMetaData = $entityManager->getClassMetadata(get_class($user));
                $log->setLoggedById($userMetaData->getSingleIdReflectionProperty()->getValue($user));
            }
            $log->setObjectClass(get_class($entity));
            $this->pendingLogInserts[spl_object_hash($entity)] = $log;
            $entityManager->persist($log);
            $unitOfWork->computeChangeSet($logClassMetadata, $log);
        }
    }

    public function postPersist(EventArgs $args)
    {

    }
}
