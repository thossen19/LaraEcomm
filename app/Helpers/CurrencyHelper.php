<?php

namespace App\Helpers;

class CurrencyHelper
{
    /**
     * Get currency symbol for the given currency code
     *
     * @param string $currency
     * @return string
     */
    public static function getSymbol($currency = 'USD')
    {
        $symbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'JPY' => '¥',
            'CAD' => 'C$',
            'AUD' => 'A$',
            'BDT' => '৳',
        ];

        return $symbols[strtoupper($currency)] ?? '$';
    }

    /**
     * Format amount with currency symbol
     *
     * @param float $amount
     * @param string $currency
     * @param string $locale
     * @return string
     */
    public static function format($amount, $currency = 'USD', $locale = 'en_US')
    {
        $symbol = self::getSymbol($currency);
        $formattedAmount = number_format($amount, 2);

        // Different positioning based on currency
        switch (strtoupper($currency)) {
            case 'EUR':
                return $formattedAmount . ' ' . $symbol;
            case 'GBP':
                return $symbol . $formattedAmount;
            case 'BDT':
                return $symbol . ' ' . $formattedAmount;
            default:
                return $symbol . $formattedAmount;
        }
    }

    /**
     * Get all available currencies
     *
     * @return array
     */
    public static function getAvailableCurrencies()
    {
        return [
            'USD' => 'USD - US Dollar',
            'EUR' => 'EUR - Euro',
            'GBP' => 'GBP - British Pound',
            'JPY' => 'JPY - Japanese Yen',
            'CAD' => 'CAD - Canadian Dollar',
            'AUD' => 'AUD - Australian Dollar',
            'BDT' => 'BDT - Bangladeshi Taka',
        ];
    }
}
