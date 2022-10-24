Feature: Calculating the fee for a loan proposal

    Scenario Outline: If the loan amount is exactly on a breakpoint, the fee is also exactly as defined in the breakpoint
        Given fee breakpoints:
            | loan amount | base fee |
            | 1000 PLN    | 50 PLN   |
            | 2000 PLN    | 90 PLN   |
        When I calculate the fee for loan amount "<loan amount>"
        Then the fee should be "<expected fee>"

        Examples:
            | loan amount | expected fee |
            | 1000 PLN    | 50 PLN       |
            | 2000 PLN    | 90 PLN       |

    Scenario Outline: If the loan amount is between breakpoints, the fee results from a linear interpolation between the surrounding breakpoints
        Given fee breakpoints:
            | loan amount | base fee |
            | 1000 PLN    | 50 PLN   |
            | 2000 PLN    | 90 PLN   |
            | 3000 PLN    | 90 PLN   |
        When I calculate the fee for loan amount "<loan amount>"
        Then the fee should be "<expected fee>"

        Examples:
            | loan amount | expected fee |
            | 1250 PLN    | 60 PLN       |
            | 1500 PLN    | 70 PLN       |
            | 2345 PLN    | 90 PLN       |

    Scenario Outline: The fee is rounded up so that the loan total is a multiple of 5
        Given fee breakpoints:
            | loan amount | base fee |
            | 10000 PLN   | 10 PLN   |
            | 14000 PLN   | 14 PLN   |
        When I calculate the fee for loan amount "<loan amount>"
        Then the fee should be "<expected fee>"

        Examples:
            | loan amount | expected fee |
            | 10001 PLN   | 14 PLN       |
            | 14000 PLN   | 15 PLN       |
