<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Application\Query;

use Brick\Money\Money;

class BreakpointQuery
{
    public function __construct(
        private readonly Money $loanAmount,
    ) {
    }

    public function loanAmount(): Money
    {
        return $this->loanAmount;
    }
}
