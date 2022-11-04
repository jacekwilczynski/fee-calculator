<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Application\CalculationChainOfResponsibility;

use Brick\Money\Money;
use LogicException;

abstract class CalculationChainOfResponsibilityHandler
{
    private ?CalculationChainOfResponsibilityHandler $next = null;

    abstract protected function supports(CalculationChainInput $input): bool;

    abstract protected function handle(CalculationChainInput $input): Money;

    final public function execute(CalculationChainInput $input): Money
    {
        if ($this->supports($input)) {
            return $this->handle($input);
        }

        if ($this->next === null) {
            throw new LogicException('No handler found for calculation.');
        }

        return $this->next->execute($input);
    }

    final public function append(CalculationChainOfResponsibilityHandler $next): self
    {
        $this->next = $next;

        return $next;
    }
}
