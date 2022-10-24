<?php

declare(strict_types=1);

namespace spec\PragmaGoTech\Interview\Infrastructure;

use PhpSpec\ObjectBehavior;
use PragmaGoTech\Interview\Model\BreakpointRepository;
use PragmaGoTech\Interview\TestUtil\Test;

class InMemoryBreakpointRepositorySpec extends ObjectBehavior
{
    function it_is_a_breakpoint_repository()
    {
        $this->shouldHaveType(BreakpointRepository::class);
    }

    function it_returns_exact_match()
    {
        $loanAmount = Test::money('1234.56');
        $matchingBreakpoint = Test::breakpoint(loanAmount: '1234.56');

        $this->setBreakpoints([
            Test::breakpoint(loanAmount: '123'),
            $matchingBreakpoint,
        ]);

        $this->findForLoanAmount($loanAmount)->shouldReturn([
            $matchingBreakpoint,
        ]);
    }

    function it_returns_first_breakpoint_if_loan_amount_is_below()
    {
        $loanAmount = Test::money('0');
        $firstBreakpoint = Test::breakpoint(loanAmount: '60');

        $this->setBreakpoints([
            $firstBreakpoint,
            Test::breakpoint('60.01'),
        ]);

        $this->findForLoanAmount($loanAmount)->shouldReturn([
            $firstBreakpoint,
        ]);
    }

    function it_returns_last_breakpoint_if_loan_amount_is_above()
    {
        $loanAmount = Test::money('120.99');
        $lastBreakpoint = Test::breakpoint(loanAmount: '60');

        $this->setBreakpoints([
            Test::breakpoint('59.99'),
            $lastBreakpoint,
        ]);

        $this->findForLoanAmount($loanAmount)->shouldReturn([
            $lastBreakpoint,
        ]);
    }

    function it_returns_breakpoint_bracket_if_loan_amount_is_between_breakpoints()
    {
        $requestedLoanAmount = Test::money('1234');
        $breakpointBelow = Test::breakpoint(loanAmount: '1200');
        $breakpointAbove = Test::breakpoint(loanAmount: '1234.01');

        $this->setBreakpoints([
            Test::breakpoint(loanAmount: '323'),
            $breakpointBelow,
            $breakpointAbove,
            Test::breakpoint(loanAmount: '2000'),
        ]);

        $this->findForLoanAmount($requestedLoanAmount)->shouldReturn([
            $breakpointBelow,
            $breakpointAbove,
        ]);
    }

    function it_returns_empty_array_if_no_breakpoints_defined()
    {
        $this->setBreakpoints([]);

        $this->findForLoanAmount(Test::money('1234.56'))->shouldReturn([]);
    }
}
