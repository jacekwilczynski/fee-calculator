<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Application\CalculationChainOfResponsibility;

use Brick\Money\Money;

class OneBreakpointCalculationHandler extends CalculationChainOfResponsibilityHandler
{
    protected function supports(CalculationChainInput $input): bool
    {
        return $input->breakpointQueryResult()->breakpointCount() === 1;
    }

    protected function handle(CalculationChainInput $input): Money
    {
        return $input->breakpointQueryResult()->firstBreakpoint()->baseFee();
    }
}
