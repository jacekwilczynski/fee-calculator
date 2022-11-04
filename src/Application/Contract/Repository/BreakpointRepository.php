<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Application\Contract\Repository;

use Brick\Money\Money;
use PragmaGoTech\Interview\Model\Breakpoint;

interface BreakpointRepository
{
    /** @return Breakpoint[] */
    public function findForLoanAmount(Money $loanAmount): array;
}
