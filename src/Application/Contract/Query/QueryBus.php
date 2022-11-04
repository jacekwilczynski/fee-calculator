<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Application\Contract\Query;

interface QueryBus
{
    public function execute(object $query): object;

    public function addHandler(QueryHandler $handler): void;
}
