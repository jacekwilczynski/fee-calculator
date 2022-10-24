<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\TestUtil;

use Brick\Money\Money;
use PragmaGoTech\Interview\Model\Breakpoint;

class Test
{
    public const DEFAULT_CURRENCY = 'PLN';
    public const DEFAULT_FEE = '0';

    public static function money(string|Money $value): Money
    {
        if ($value instanceof Money) {
            return $value;
        }

        $parts = explode(' ', $value);

        return Money::of($parts[0], $parts[1] ?? self::DEFAULT_CURRENCY);
    }

    public static function breakpoint(
        string|Money $loanAmount,
        string|Money $baseFee = self::DEFAULT_FEE,
    ): Breakpoint {
        return new Breakpoint(
            self::money($loanAmount),
            self::money($baseFee),
        );
    }
}
