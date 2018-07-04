<?php declare(strict_types=1);

namespace Incompass\LoggableBundle\Processor;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class RequestProcessor
 *
 * @package Incompass\LoggableBundle\Processor
 * @author  Mike Bates <mike@casechek.com>
 * @author  Joe Mizzi <joe@casechek.com>
 * @author  James Matsumura <james@casechek.com>
 */
class RequestProcessor
{
    /**
     * @var RequestStack
     */
    protected $request;

    /**
     * RequestProcessor constructor.
     * @param RequestStack $request
     */
    public function __construct(RequestStack $request)
    {
        $this->request = $request;
    }

    /**
     * @param array $record
     * @return array
     */
    public function processRecord(array $record)
    {
        $request = $this->request->getCurrentRequest();
        $record['extra']['client_ip'] = $request->getClientIp();
        $record['extra']['client_port']  = $request->getPort();
        $record['extra']['uri'] = $request->getUri();
        $record['extra']['query_string'] = $request->getQueryString();
        $record['extra']['method'] = $request->getMethod();
        $record['extra']['request'] = $request->request->all();

        return $record;
    }
}