<?php

declare(strict_types=1);

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Brick\Money\Money;
use PragmaGoTech\Interview\Application\Contract\FeeCalculator;
use PragmaGoTech\Interview\Application\Contract\Repository\BreakpointRepository;
use PragmaGoTech\Interview\Infrastructure\InMemoryBreakpointRepository;
use PragmaGoTech\Interview\Infrastructure\ServiceConfig;
use PragmaGoTech\Interview\Infrastructure\ServiceLocator;
use PragmaGoTech\Interview\Model\Breakpoint;
use PragmaGoTech\Interview\Model\LoanProposal;
use PragmaGoTech\Interview\TestUtil\Test;

class FeatureContext implements Context
{
    private InMemoryBreakpointRepository $breakpointRepository;
    private FeeCalculator $feeCalculator;
    private Money $fee;

    public function __construct()
    {
        $serviceLocator = ServiceConfig::applyTo(new ServiceLocator());
        $this->breakpointRepository = $serviceLocator->get(BreakpointRepository::class);
        $this->feeCalculator = $serviceLocator->get(FeeCalculator::class);
    }

    /**
     * @Given fee breakpoints:
     */
    public function feeBreakpoints(TableNode $table)
    {
        $this->breakpointRepository->setBreakpoints(array_map(
            fn(array $row) => new Breakpoint(
                Test::money($row['loan amount']),
                Test::money($row['base fee']),
            ),
            $table->getColumnsHash(),
        ));
    }

    /**
     * @When I calculate the fee for loan amount :amountString
     */
    public function iCalculateTheFeeForLoanAmount(string $amountString)
    {
        $loanProposal = new LoanProposal(Test::money($amountString));
        $this->fee = $this->feeCalculator->calculate($loanProposal);
    }

    /**
     * @Then the fee should be :expectedFeeString
     */
    public function theFeeShouldBe(string $expectedFeeString)
    {
        $expectedFee = Test::money($expectedFeeString);

        if (!$this->fee->isAmountAndCurrencyEqualTo($expectedFee)) {
            throw new Exception("Resulting fee was $this->fee, $expectedFee expected.");
        }
    }
}
