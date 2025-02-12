<?php

namespace App\Services;

class CurrencyConversionService
{
    /**
     * Simulated exchange rates relative to USD.
     * Replace these values with live API data as needed.
     */
    protected $rates = [
        'USD' => 1.00,
        'EUR' => 0.92,
        'GBP' => 0.81,
        // Add additional currencies as needed.
    ];

    /**
     * Convert an amount from one currency to another.
     *
     * @param  float  $amount
     * @param  string $fromCurrency (e.g., USD)
     * @param  string $toCurrency (e.g., EUR)
     * @return float
     *
     * @throws \Exception if a currency is not supported.
     */
    public function convert($amount, $fromCurrency, $toCurrency)
    {
        // If the currencies are the same, no conversion is needed.
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        // Check if both currencies exist in our rates.
        if (!isset($this->rates[$fromCurrency]) || !isset($this->rates[$toCurrency])) {
            throw new \Exception("Exchange rate for one of the currencies is not available.");
        }

        // Convert the amount to USD first (our base) then to target currency.
        $usdAmount = $amount / $this->rates[$fromCurrency];
        $converted = $usdAmount * $this->rates[$toCurrency];

        return $converted;
    }

    /**
     * Optionally update exchange rates from an external API.
     */
    public function updateRates()
    {
        // Implement API calls here and update $this->rates
    }
}
