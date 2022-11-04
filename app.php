<?php

declare(strict_types=1);

use Brick\Money\Money;
use PragmaGoTech\Interview\Application\Contract\FeeCalculator as FeeCalculatorInterface;
use PragmaGoTech\Interview\Infrastructure\ServiceConfig;
use PragmaGoTech\Interview\Infrastructure\ServiceLocator;
use PragmaGoTech\Interview\Model\LoanProposal;

require_once __DIR__ . '/vendor/autoload.php';

$answer = $argv[1] ?? readline("How much PLN do you want? ");
$proposal = new LoanProposal(Money::of($answer, 'PLN'));
$fee = getFeeCalculator()->calculate($proposal);

echo sprintf(
    "You'll pay a %s fee, bringing the total cost to %s.\n",
    $fee,
    $proposal->amount()->plus($fee),
);

function getFeeCalculator(): FeeCalculatorInterface
{
    $serviceLocator = ServiceConfig::applyTo(new ServiceLocator());

    /** @var FeeCalculatorInterface $calculator */
    $calculator = $serviceLocator->get(FeeCalculatorInterface::class);

    return $calculator;
}
