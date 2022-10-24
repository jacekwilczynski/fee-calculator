<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Model;

use Brick\Money\Money;

interface BreakpointRepository
{
    /** @return Breakpoint[] */
    public function findForLoanAmount(Money $loanAmount): array;
}
