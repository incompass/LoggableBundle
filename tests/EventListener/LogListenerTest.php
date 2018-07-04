<?php

namespace Incompass\LoggableBundle\Tests;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\UnitOfWork;
use Incompass\LoggableBundle\Log;
use Incompass\LoggableBundle\LogListener;
use Mockery;
use PHPUnit_Framework_TestCase;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class LogListenerTests
 *
 * @package Incompass\LoggableBundle\tests
 * @author  Joe Mizzi <joe@casechek.com>
 * @author  Mike Bates <mike@casechek.com>
 */
class LogListenerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_logs_an_entity_insert_with_a_user_id()
    {
        $entityStub = new EntityStub();

        $userMock = Mockery::mock(UserInterface::class);
        $userMock->shouldReceive('getId')
            ->andReturn(1);

        $tokenMock = Mockery::mock(TokenInterface::class);
        $tokenMock->shouldReceive('getUser')
            ->andReturn($userMock);

        $tokenStorageMock = Mockery::mock(TokenStorageInterface::class);
        $tokenStorageMock->shouldReceive('getToken')
            ->andReturn($tokenMock);

        $logMock = Mockery::mock(Log::class);
        $logMock->shouldReceive('setAction')
            ->with(LogListener::ACTION_CREATED);
        $logMock->shouldReceive('setLoggedAt')
            ->once();
        $logMock->shouldReceive('setLoggedById')
            ->with(1);
        $logMock->shouldReceive('setObjectClass')
            ->with(EntityStub::class);
        $logMock->shouldReceive('setData')
            ->with(json_encode(['some' => 'data']));

        $classMetadataMock = Mockery::mock(ClassMetadata::class);
        $classMetadataMock->shouldReceive('newInstance')
            ->andReturn($logMock);

        $unitOfWorkMock = Mockery::mock(UnitOfWork::class);
        $unitOfWorkMock->shouldReceive('computeChangeSet')
            ->with($classMetadataMock, $logMock);
        $unitOfWorkMock->shouldReceive('getEntityChangeSet')
            ->with($entityStub)
            ->andReturn(['some' => 'data']);
        $unitOfWorkMock->shouldReceive('getScheduledEntityInsertions')
            ->once()
            ->andReturn([$entityStub]);

        $reflectionPropertyMock = Mockery::mock(ReflectionProperty::class);
        $reflectionPropertyMock->shouldReceive('getValue')
            ->andReturn(1);
        $userClassMetadaMock = Mockery::mock(ClassMetadata::class);
        $userClassMetadaMock->shouldReceive('getSingleIdReflectionProperty')
            ->andReturn($reflectionPropertyMock);
        $entityManagerMock = Mockery::mock(EntityManager::class);
        $entityManagerMock->shouldReceive('getUnitOfWork')
            ->andReturn($unitOfWorkMock);
        $entityManagerMock->shouldReceive('getClassMetadata')
            ->with(Log::class)
            ->andReturn($classMetadataMock);
        $entityManagerMock->shouldReceive('getClassMetadata')
            ->with(get_class($userMock))
            ->andReturn($userClassMetadaMock);
        $entityManagerMock->shouldReceive('persist')
            ->once()
            ->with($logMock);

        $onFlushEventArgsMock = Mockery::mock(OnFlushEventArgs::class);
        $onFlushEventArgsMock->shouldReceive('getEntityManager')
            ->andReturn($entityManagerMock);

        $logListener = new LogListener($tokenStorageMock);
        $logListener->onFlush($onFlushEventArgsMock);
        $reflectionClass = new ReflectionClass($logListener);
        $reflectionProperty = $reflectionClass->getProperty('pendingLogInserts');
        $reflectionProperty->setAccessible(true);
        $pendingLogInserts = $reflectionProperty->getValue($logListener);
        self::assertEquals($logMock, $pendingLogInserts[spl_object_hash($entityStub)]);
    }
}
