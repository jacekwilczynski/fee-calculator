<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Application;

use Brick\Math\RoundingMode;
use Brick\Money\Money;
use PragmaGoTech\Interview\Application\Contract\MultipleBreakpointHandlingStrategy;

class LinearInterpolationStrategy implements MultipleBreakpointHandlingStrategy
{
    public function handle(Money $loanAmount, array $breakpoints): Money
    {
        [$lowerBreakpoint, $upperBreakpoint] = $breakpoints;
        $amountSpace = $upperBreakpoint->loanAmount()->minus($lowerBreakpoint->loanAmount());
        $feeSpace = $upperBreakpoint->baseFee()->minus($lowerBreakpoint->baseFee());

        $amountMinusLower = $loanAmount->minus($lowerBreakpoint->loanAmount());

        $feeMinusLower = $amountMinusLower
            ->toRational()
            ->dividedBy($amountSpace->getAmount())
            ->multipliedBy($feeSpace->getAmount())
            ->to($feeSpace->getContext(), RoundingMode::UP);

        return $feeMinusLower->plus($lowerBreakpoint->baseFee());
    }
}
