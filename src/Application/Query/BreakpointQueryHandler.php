<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Application\Query;

use DomainException;
use PragmaGoTech\Interview\Application\Contract\Query\QueryHandler;
use PragmaGoTech\Interview\Application\Contract\Repository\BreakpointRepository;

class BreakpointQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly BreakpointRepository $breakpointRepository,
    ) {
    }

    public function handle(object $query): BreakpointQueryResult
    {
        /** @var BreakpointQuery $query */

        $breakpoints = $this->breakpointRepository->findForLoanAmount($query->loanAmount());

        if ($breakpoints === []) {
            throw new DomainException('No breakpoints defined.');
        }

        return new BreakpointQueryResult($breakpoints);
    }

    public function getHandledClass(): string
    {
        return BreakpointQuery::class;
    }
}
