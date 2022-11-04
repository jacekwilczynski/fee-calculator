<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Application\Contract;

use Brick\Money\Money;
use PragmaGoTech\Interview\Model\Breakpoint;

interface MultipleBreakpointHandlingStrategy
{
    /**
     * @param Money $loanAmount
     * @param Breakpoint[] $breakpoints
     * @return Money
     */
    public function handle(Money $loanAmount, array $breakpoints): Money;
}
