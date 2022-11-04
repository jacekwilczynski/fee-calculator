<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Infrastructure;

use Brick\Money\Money;
use PragmaGoTech\Interview\Application\CalculationChainOfResponsibility\OneBreakpointCalculationHandler;
use PragmaGoTech\Interview\Application\CalculationChainOfResponsibility\TwoBreakpointCalculationHandler;
use PragmaGoTech\Interview\Application\Contract\FeeCalculator as FeeCalculatorInterface;
use PragmaGoTech\Interview\Application\Contract\Repository\BreakpointRepository;
use PragmaGoTech\Interview\Application\FeeCalculator;
use PragmaGoTech\Interview\Application\LinearInterpolationStrategy;
use PragmaGoTech\Interview\Application\Query\BreakpointQueryHandler;
use PragmaGoTech\Interview\Application\RoundingFeeCalculatorDecorator;
use PragmaGoTech\Interview\Infrastructure\QueryBus\HandlerMap;
use PragmaGoTech\Interview\Infrastructure\QueryBus\QueryBus;
use PragmaGoTech\Interview\Model\Breakpoint;

class ServiceConfig
{
    public static function applyTo(ServiceLocator $serviceLocator): ServiceLocator
    {
        $breakpointRepository = new InMemoryBreakpointRepository();
        $breakpointRepository->setBreakpoints([
            new Breakpoint(Money::of('1000', 'PLN'), Money::of('50', 'PLN')),
            new Breakpoint(Money::of('2000', 'PLN'), Money::of('90', 'PLN')),
            new Breakpoint(Money::of('3000', 'PLN'), Money::of('90', 'PLN')),
            new Breakpoint(Money::of('4000', 'PLN'), Money::of('115', 'PLN')),
            new Breakpoint(Money::of('5000', 'PLN'), Money::of('100', 'PLN')),
            new Breakpoint(Money::of('6000', 'PLN'), Money::of('120', 'PLN')),
            new Breakpoint(Money::of('7000', 'PLN'), Money::of('140', 'PLN')),
            new Breakpoint(Money::of('8000', 'PLN'), Money::of('160', 'PLN')),
            new Breakpoint(Money::of('9000', 'PLN'), Money::of('180', 'PLN')),
            new Breakpoint(Money::of('10000', 'PLN'), Money::of('200', 'PLN')),
            new Breakpoint(Money::of('11000', 'PLN'), Money::of('220', 'PLN')),
            new Breakpoint(Money::of('12000', 'PLN'), Money::of('240', 'PLN')),
            new Breakpoint(Money::of('13000', 'PLN'), Money::of('260', 'PLN')),
            new Breakpoint(Money::of('14000', 'PLN'), Money::of('280', 'PLN')),
            new Breakpoint(Money::of('15000', 'PLN'), Money::of('300', 'PLN')),
            new Breakpoint(Money::of('16000', 'PLN'), Money::of('320', 'PLN')),
            new Breakpoint(Money::of('17000', 'PLN'), Money::of('340', 'PLN')),
            new Breakpoint(Money::of('18000', 'PLN'), Money::of('360', 'PLN')),
            new Breakpoint(Money::of('19000', 'PLN'), Money::of('380', 'PLN')),
            new Breakpoint(Money::of('20000', 'PLN'), Money::of('400', 'PLN')),
        ]);

        $queryBus = new QueryBus(new HandlerMap());
        $queryBus->addHandler(new BreakpointQueryHandler($breakpointRepository));

        $calculationHandler = new OneBreakpointCalculationHandler();
        $calculationHandler->append(new TwoBreakpointCalculationHandler(new LinearInterpolationStrategy()));

        $feeCalculator = new RoundingFeeCalculatorDecorator(
            new FeeCalculator($queryBus, $calculationHandler),
        );

        $serviceLocator->setService(BreakpointRepository::class, $breakpointRepository);
        $serviceLocator->setService(FeeCalculatorInterface::class, $feeCalculator);

        return $serviceLocator;
    }
}
