<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Application\Query;

use PragmaGoTech\Interview\Model\Breakpoint;

class BreakpointQueryResult
{
    /**
     * @param Breakpoint[] $breakpoints
     */
    public function __construct(
        private readonly array $breakpoints,
    ) {
    }

    public function breakpointCount(): int
    {
        return count($this->breakpoints);
    }

    public function firstBreakpoint(): Breakpoint
    {
        return $this->breakpoints[0];
    }

    /**
     * @return Breakpoint[]
     */
    public function breakpoints(): array
    {
        return $this->breakpoints;
    }
}
