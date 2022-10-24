<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview;

use Brick\Math\RoundingMode;
use Brick\Money\Money;
use DomainException;
use PragmaGoTech\Interview\Model\Breakpoint;
use PragmaGoTech\Interview\Model\BreakpointRepository;
use PragmaGoTech\Interview\Model\LoanProposal;

class FeeCalculator
{
    private const ROUNDING_STEP = 500;

    public function __construct(
        private readonly BreakpointRepository $breakpointRepository,
    ) {
    }

    /**
     * @return Money The calculated total fee.
     */
    public function calculate(LoanProposal $loanProposal): Money
    {
        $loanProposalAmount = $loanProposal->amount();

        $breakpoints = $this->breakpointRepository->findForLoanAmount($loanProposalAmount)
            ?: throw new DomainException('No fee breakpoints defined.');

        $feeBeforeRounding = count($breakpoints) === 1
            ? $breakpoints[0]->baseFee()
            : $this->interpolate($breakpoints, $loanProposalAmount);

        return $this->round($feeBeforeRounding, $loanProposalAmount);
    }

    /**
     * @param Breakpoint[] $breakpoints
     * @param Money $loanProposalAmount
     * @return Money
     */
    private function interpolate(array $breakpoints, Money $loanProposalAmount): Money
    {
        [$lowerBreakpoint, $upperBreakpoint] = $breakpoints;
        $amountSpace = $upperBreakpoint->loanAmount()->minus($lowerBreakpoint->loanAmount());
        $feeSpace = $upperBreakpoint->baseFee()->minus($lowerBreakpoint->baseFee());

        $amountMinusLower = $loanProposalAmount->minus($lowerBreakpoint->loanAmount());

        $feeMinusLower = $amountMinusLower
            ->toRational()
            ->dividedBy($amountSpace->getAmount())
            ->multipliedBy($feeSpace->getAmount())
            ->to($feeSpace->getContext(), RoundingMode::UP);

        return $feeMinusLower->plus($lowerBreakpoint->baseFee());
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
