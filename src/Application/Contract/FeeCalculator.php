<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Application\Contract;

use Brick\Money\Money;
use PragmaGoTech\Interview\Model\LoanProposal;

interface FeeCalculator
{
    /**
     * @return Money The calculated total fee.
     */
    public function calculate(LoanProposal $loanProposal): Money;
}
