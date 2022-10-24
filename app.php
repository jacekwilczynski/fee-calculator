<?php

declare(strict_types=1);

use Brick\Money\Money;
use PragmaGoTech\Interview\FeeCalculator;
use PragmaGoTech\Interview\Infrastructure\InMemoryBreakpointRepository;
use PragmaGoTech\Interview\Model\Breakpoint;
use PragmaGoTech\Interview\Model\LoanProposal;

require_once __DIR__ . '/vendor/autoload.php';

$answer = $argv[1] ?? readline("How much PLN do you want? ");
$proposal = new LoanProposal(pln($answer));
$fee = getFeeCalculator()->calculate($proposal);

echo sprintf(
    "You'll pay a %s fee, bringing the total cost to %s.\n",
    $fee,
    $proposal->amount()->plus($fee),
);

function getFeeCalculator(): FeeCalculator
{
    $breakpointRepository = new InMemoryBreakpointRepository();
    $breakpointRepository->setBreakpoints([
        new Breakpoint(pln('1000'), pln('50')),
        new Breakpoint(pln('2000'), pln('90')),
        new Breakpoint(pln('3000'), pln('90')),
        new Breakpoint(pln('4000'), pln('115')),
        new Breakpoint(pln('5000'), pln('100')),
        new Breakpoint(pln('6000'), pln('120')),
        new Breakpoint(pln('7000'), pln('140')),
        new Breakpoint(pln('8000'), pln('160')),
        new Breakpoint(pln('9000'), pln('180')),
        new Breakpoint(pln('10000'), pln('200')),
        new Breakpoint(pln('11000'), pln('220')),
        new Breakpoint(pln('12000'), pln('240')),
        new Breakpoint(pln('13000'), pln('260')),
        new Breakpoint(pln('14000'), pln('280')),
        new Breakpoint(pln('15000'), pln('300')),
        new Breakpoint(pln('16000'), pln('320')),
        new Breakpoint(pln('17000'), pln('340')),
        new Breakpoint(pln('18000'), pln('360')),
        new Breakpoint(pln('19000'), pln('380')),
        new Breakpoint(pln('20000'), pln('400')),
    ]);

    return new FeeCalculator($breakpointRepository);
}

function pln(string $value): Money
{
    return Money::of($value, 'PLN');
}
