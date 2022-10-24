<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Model;

use Brick\Money\Money;

/**
 * A cut down version of a loan application containing
 * only the required properties for this test.
 */
class LoanProposal
{
    public function __construct(
        private readonly Money $amount,
    ) {
    }

    /**
     * Amount requested for this loan application.
     */
    public function amount(): Money
    {
        return $this->amount;
    }
}
