<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Infrastructure;

use Brick\Money\Money;
use PragmaGoTech\Interview\Application\Contract\Repository\BreakpointRepository;
use PragmaGoTech\Interview\Model\Breakpoint;

class InMemoryBreakpointRepository implements BreakpointRepository
{
    /** @var Breakpoint[] */
    private array $breakpoints = [];

    /** @inheritDoc */
    public function findForLoanAmount(Money $loanAmount): array
    {
        if ($this->breakpoints === []) {
            return [];
        }

        /** @var Breakpoint|null $previous */
        $previous = null;

        foreach ($this->breakpoints as $current) {
            $breakpointLoanAmount = $current->loanAmount();

            if ($breakpointLoanAmount->isEqualTo($loanAmount)) {
                return [$current];
            }

            if ($breakpointLoanAmount->isGreaterThan($loanAmount)) {
                return array_values(array_filter([$previous, $current]));
            }

            $previous = $current;
        }

        return [$current];
    }

    /**
     * @param Breakpoint[] $breakpoints Breakpoints sorted by loan amount ascending.
     */
    public function setBreakpoints(array $breakpoints): void
    {
        $this->breakpoints = $breakpoints;
    }
}
