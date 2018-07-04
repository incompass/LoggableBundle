<?php declare(strict_types=1);

namespace Incompass\LoggableBundle\DependencyInjection;

use Incompass\LoggableBundle\EventListener\LogListener;
use Incompass\LoggableBundle\Monolog\DatabaseLogHandler;
use Incompass\LoggableBundle\Processor\RequestProcessor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class LoggableExtension
 *
 * @package Incompass\LoggableBundle\DependencyInjection
 * @author  Mike Bates <mike@casechek.com>
 * @author  Joe Mizzi <joe@casechek.com>
 * @author  James Matsumura <james@casechek.com>
 */
class LoggableExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @param array $configs An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\BadMethodCallException
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $container->register(LogListener::class, LogListener::class)
            ->addTag('doctrine.event_listener', ['event' => 'onFlush', 'priority' => -9999]);

        $container->register(DatabaseLogHandler::class, DatabaseLogHandler::class)
            ->addArgument(new Reference('doctrine.orm.entity_manager'));

        $container->register(RequestProcessor::class, RequestProcessor::class)
            ->addArgument(new Reference('request_stack'))
            ->addArgument(new Reference('service_container'))
            ->addTag('monolog.processor', ['method' => 'processRecord', 'handler' => 'db']);
    }
}