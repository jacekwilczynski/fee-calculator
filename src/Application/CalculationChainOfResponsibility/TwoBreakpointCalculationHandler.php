<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Application\CalculationChainOfResponsibility;

use Brick\Money\Money;
use PragmaGoTech\Interview\Application\Contract\MultipleBreakpointHandlingStrategy;

class TwoBreakpointCalculationHandler extends CalculationChainOfResponsibilityHandler
{
    public function __construct(
        private readonly MultipleBreakpointHandlingStrategy $strategy,
    ) {
    }

    protected function supports(CalculationChainInput $input): bool
    {
        return $input->breakpointQueryResult()->breakpointCount() === 2;
    }

    protected function handle(CalculationChainInput $input): Money
    {
        return $this->strategy->handle(
            $input->loanProposal()->amount(),
            $input->breakpointQueryResult()->breakpoints(),
        );
    }
}
