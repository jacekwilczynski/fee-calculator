<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Application\Contract\Query;

interface QueryHandler
{
    public function handle(object $query): object;

    public function getHandledClass(): string;
}
