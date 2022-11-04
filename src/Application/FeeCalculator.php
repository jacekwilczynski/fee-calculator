<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Application;

use Brick\Money\Money;
use PragmaGoTech\Interview\Application\CalculationChainOfResponsibility\CalculationChainInput;
use PragmaGoTech\Interview\Application\CalculationChainOfResponsibility\CalculationChainOfResponsibilityHandler;
use PragmaGoTech\Interview\Application\Contract\Query\QueryBus;
use PragmaGoTech\Interview\Application\Query\BreakpointQuery;
use PragmaGoTech\Interview\Application\Query\BreakpointQueryResult;
use PragmaGoTech\Interview\Model\LoanProposal;

class FeeCalculator implements Contract\FeeCalculator
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly CalculationChainOfResponsibilityHandler $calculationHandler,
    ) {
    }

    public function calculate(LoanProposal $loanProposal): Money
    {
        /** @var BreakpointQueryResult $queryResult */
        $queryResult = $this->queryBus->execute(new BreakpointQuery($loanProposal->amount()));

        $calculationChainInput = new CalculationChainInput($loanProposal, $queryResult);

        return $this->calculationHandler->execute($calculationChainInput);
    }
}
