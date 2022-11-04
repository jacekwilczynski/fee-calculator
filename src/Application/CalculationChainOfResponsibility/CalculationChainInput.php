<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Application\CalculationChainOfResponsibility;

use PragmaGoTech\Interview\Application\Query\BreakpointQueryResult;
use PragmaGoTech\Interview\Model\LoanProposal;

class CalculationChainInput
{
    public function __construct(
        private readonly LoanProposal $loanProposal,
        private readonly BreakpointQueryResult $breakpointQueryResult,
    ) {
    }

    public function loanProposal(): LoanProposal
    {
        return $this->loanProposal;
    }

    public function breakpointQueryResult(): BreakpointQueryResult
    {
        return $this->breakpointQueryResult;
    }
}
