<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Model;

use Brick\Money\Money;

class Breakpoint
{
    public function __construct(
        private readonly Money $loanAmount,
        private readonly Money $baseFee,
    ) {
    }

    public function loanAmount(): Money
    {
        return $this->loanAmount;
    }

    public function baseFee(): Money
    {
        return $this->baseFee;
    }
}
