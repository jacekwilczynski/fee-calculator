<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Infrastructure\QueryBus;

use PragmaGoTech\Interview\Application\Contract\Query\QueryBus as QueryBusInterface;
use PragmaGoTech\Interview\Application\Contract\Query\QueryHandler;

class QueryBus implements QueryBusInterface
{
    private HandlerMap $handlerMap;

    public function __construct(HandlerMap $handlerMap)
    {
        $this->handlerMap = $handlerMap;
    }

    public function execute(object $query): object
    {
        $handler = $this->handlerMap->getHandler($query);

        return $handler->handle($query);
    }

    public function addHandler(QueryHandler $handler): void
    {
        $this->handlerMap->setHandler($handler->getHandledClass(), $handler);
    }
}
