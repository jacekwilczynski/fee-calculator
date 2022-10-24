<?php

declare(strict_types=1);

namespace spec\PragmaGoTech\Interview;

use Brick\Money\Money;
use DomainException;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;
use PragmaGoTech\Interview\FeeCalculator;
use PragmaGoTech\Interview\Model\Breakpoint;
use PragmaGoTech\Interview\Model\BreakpointRepository;
use PragmaGoTech\Interview\Model\LoanProposal;
use PragmaGoTech\Interview\TestUtil\Test;

class FeeCalculatorSpec extends ObjectBehavior
{
    function let(BreakpointRepository $breakpointRepository)
    {
        $this->beConstructedWith($breakpointRepository);
    }

    function it_is_a_fee_calculator()
    {
        $this->shouldHaveType(FeeCalculator::class);
    }

    function it_returns_fee_as_in_breakpoint_if_one_breakpoint_found(
        BreakpointRepository $breakpointRepository,
    ) {
        $loanAmount = Test::money('70');
        $fee = Test::money('5');
        $breakpoint = new Breakpoint($loanAmount, $fee);

        $breakpointRepository
            ->findForLoanAmount($loanAmount)
            ->shouldBeCalledOnce()
            ->willReturn([$breakpoint]);

        $this->calculate(new LoanProposal($loanAmount))->shouldEqualMoney($fee);
    }

    function it_returns_interpolated_value_if_two_breakpoints_found(
        BreakpointRepository $breakpointRepository,
    ) {
        $loanAmount = Test::money('25');
        $breakpointBelow = Test::breakpoint(loanAmount: '10', baseFee: '5');
        $breakpointAbove = Test::breakpoint(loanAmount: '40', baseFee: '15');

        $breakpointRepository
            ->findForLoanAmount($loanAmount)
            ->willReturn([$breakpointBelow, $breakpointAbove]);

        $this->calculate(new LoanProposal($loanAmount))->shouldEqualMoney('10');
    }

    function it_rounds_fee_up_so_that_total_is_always_a_multiple_of_five(
        BreakpointRepository $breakpointRepository,
    ) {
        $loanAmount = Test::money('9.15');
        $breakpoint = Test::breakpoint($loanAmount, baseFee: '4.40');

        $breakpointRepository
            ->findForLoanAmount($loanAmount)
            ->willReturn([$breakpoint]);

        $this->calculate(new LoanProposal($loanAmount))->shouldEqualMoney('5.85');
    }

    function it_throws_if_no_breakpoints_found(
        BreakpointRepository $breakpointRepository
    ) {
        $loanAmount = Test::money('70');

        $breakpointRepository
            ->findForLoanAmount($loanAmount)
            ->willReturn([]);

        $this->shouldThrow(DomainException::class)->duringCalculate(new LoanProposal($loanAmount));
    }

    public function getMatchers(): array
    {
        return [
            'equalMoney' => function (Money $subject, string|Money $key): bool {
                $expectedMoney = Test::money($key);

                if (!$subject->isAmountAndCurrencyEqualTo($expectedMoney)) {
                    throw new FailureException(sprintf(
                        'Expected result to equal %s, got %s.',
                        $expectedMoney,
                        $subject,
                    ));
                }

                return true;
            },
        ];
    }
}
