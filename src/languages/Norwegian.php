<?php

namespace js\tools\numbers2words\languages;

use js\tools\numbers2words\exceptions\UnsupportedCurrencyException;
use js\tools\numbers2words\Speller;

/**
 * @internal
 */
class Norwegian extends Language
{
    public function spellMinus(): string
    {
        return 'minus';
    }

    public function spellMinorUnitSeparator(): string
    {
        return 'og';
    }

    public function spellHundred(int $number, int $groupOfThrees, bool $isDecimalPart, string $currency): string
    {
        static $hundreds = [
            1 => 'ett hundre',
            2 => 'to hundre',
            3 => 'tre hundre',
            4 => 'fire hundre',
            5 => 'fem hundre',
            6 => 'seks hundre',
            7 => 'syv hundre',
            8 => 'åtte hundre',
            9 => 'ni hundre',
        ];
        static $tens = [
            1 => 'ti',
            2 => 'tjue',
            3 => 'tretti',
            4 => 'førti',
            5 => 'femti',
            6 => 'seksti',
            7 => 'sytti',
            8 => 'åtti',
            9 => 'nitti',
        ];
        static $teens = [
            11 => 'elleve',
            12 => 'tolv',
            13 => 'tretten',
            14 => 'fjorten',
            15 => 'femten',
            16 => 'seksten',
            17 => 'sytten',
            18 => 'atten',
            19 => 'nitten',
        ];

        $text = '';

        if ($number >= 100)
        {
            $text .= $hundreds[intval(substr("$number", 0, 1))];
            $number = $number % 100;

            if ($number === 0) // exact hundreds
            {
                return $text;
            }

            $text .= ' ';
        }

        if ($number < 10)
        {
            $text .= $this->spellSingle($number, $isDecimalPart, $currency);
        }
        else if (($number > 10) && ($number < 20))
        {
            $text .= $teens[$number];
        }
        else
        {
            $text .= $tens[intval(substr($number, 0, 1))];

            if ($number % 10 > 0)
            {
                $text .= ' ' . $this->spellSingle($number % 10, $isDecimalPart, $currency);
            }
        }

        return $text;
    }

    private function spellSingle(int $digit, bool $isDecimalPart, string $currency): string
    {
        static $singlesMasculine = [
            0 => 'null',
            1 => 'en',
            2 => 'to',
            3 => 'tre',
            4 => 'fire',
            5 => 'fem',
            6 => 'seks',
            7 => 'syv',
            8 => 'åtte',
            9 => 'ni',
        ];
        static $singlesFeminine = [
            0 => 'null',
            1 => 'ei',
            2 => 'to',
            3 => 'tre',
            4 => 'fire',
            5 => 'fem',
            6 => 'seks',
            7 => 'syv',
            8 => 'åtte',
            9 => 'ni',
        ];

        $feminineCurrencies = [
            Speller::CURRENCY_RUSSIAN_ROUBLE => $isDecimalPart, // Russian kopeks (but not rubles)
            Speller::CURRENCY_BRITISH_POUND  => !$isDecimalPart, // British pounds (but not pennies)
        ];

        if (!empty($feminineCurrencies[$currency]))
        {
            return $singlesFeminine[$digit];
        }

        return $singlesMasculine[$digit];
    }

    public function spellExponent(string $type, int $number, string $currency): string
    {
        $tens = $number % 100;
        $singles = $number % 10;

        if ($type === 'million')
        {
            if (($singles === 1) && ($tens !== 11))
            {
                return 'million';
            }

            return 'millioner';
        }

        if ($type === 'thousand')
        {
            if (($singles === 1) && ($tens !== 11))
            {
                return 'tusen';
            }

            return 'tusen';
        }

        return '';
    }

	public function getCurrencyNameMajor(int $amount, string $currency): string
	{
		$names = Currencies::getCurrencyNameMajor();
		return self::getCurrencyName($names, $amount, $currency);
	}
	
	public function getCurrencyNameMinor(int $amount, string $currency): string
	{
		$names = Currencies::getCurrencyNameMinor();
		return self::getCurrencyName($names, $amount, $currency);
	}

    private static function getCurrencyName(array $names, int $amount, string $currency): string
	{
		$index = (($amount === 1) ? 0 : 1);
		
		return $names[$currency][$index] ?? self::throw(new UnsupportedCurrencyException($currency));
	}
}
