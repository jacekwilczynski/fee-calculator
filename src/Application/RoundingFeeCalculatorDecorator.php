<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Application;

use Brick\Math\RoundingMode;
use Brick\Money\Money;
use PragmaGoTech\Interview\Application\Contract\FeeCalculator as FeeCalculatorInterface;
use PragmaGoTech\Interview\Model\LoanProposal;

class RoundingFeeCalculatorDecorator implements FeeCalculatorInterface
{
    private const ROUNDING_STEP = 500;

    public function __construct(
        private readonly FeeCalculatorInterface $decorated,
    ) {
    }

    public function calculate(LoanProposal $loanProposal): Money
    {
        $resultBeforeRounding = $this->decorated->calculate($loanProposal);

        return $this->round($resultBeforeRounding, $loanProposal->amount());
    }


    private function round(Money $feeBeforeRounding, Money $loanProposalAmount): Money
    {
        $totalBeforeRounding = $feeBeforeRounding->plus($loanProposalAmount);
        $total = $totalBeforeRounding
            ->dividedBy(self::ROUNDING_STEP, RoundingMode::UP)
            ->multipliedBy(self::ROUNDING_STEP);

        return $total->minus($loanProposalAmount);
    }
}
