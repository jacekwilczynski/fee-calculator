<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Infrastructure\QueryBus;

use OutOfRangeException;
use PragmaGoTech\Interview\Application\Contract\Query\QueryHandler;

class HandlerMap
{
    /**
     * @var array<class-string, QueryHandler>
     */
    private array $handlersByQueryClass = [];

    public function getHandler(object $query): QueryHandler
    {
        $queryClass = get_class($query);

        $handler = $this->handlersByQueryClass[$queryClass] ?? null;

        if ($handler === null) {
            throw new OutOfRangeException(sprintf(
                'No handler for query class %s has been registered.',
                $queryClass,
            ));
        }

        return $handler;
    }

    public function setHandler(string $queryClass, QueryHandler $handler): void
    {
        $this->handlersByQueryClass[$queryClass] = $handler;
    }
}
