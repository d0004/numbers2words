<?php

namespace js\tools\numbers2words\languages;

use js\tools\numbers2words\Speller;

class Currencies
{

    public static function getCurrencyNameMajor()
    {
        return [
            Speller::CURRENCY_EURO                => ['euro', 'euro'],
			Speller::CURRENCY_BRITISH_POUND       => ['pound', 'pounds'],
			Speller::CURRENCY_LATVIAN_LAT         => ['lat', 'lats'],
			Speller::CURRENCY_LITHUANIAN_LIT      => ['litas', 'litai'],
			Speller::CURRENCY_RUSSIAN_ROUBLE      => ['ruble', 'rubles'],
			Speller::CURRENCY_US_DOLLAR           => ['dollar', 'dollars'],
			Speller::CURRENCY_PL_ZLOTY            => ['zloty', 'zlote'],
			Speller::CURRENCY_TANZANIAN_SHILLING  => ['shilling', 'shillings'],
			Speller::CURRENCY_NORWEGIAN_KRONE     => ['krone', 'krone'],
        ];
    }

    public static function getCurrencyNameMinor()
    {
        return [
            Speller::CURRENCY_EURO                => ['cent', 'cents'],
			Speller::CURRENCY_BRITISH_POUND       => ['penny', 'pennies'],
			Speller::CURRENCY_LATVIAN_LAT         => ['santim', 'santims'],
			Speller::CURRENCY_LITHUANIAN_LIT      => ['centas', 'centai'],
			Speller::CURRENCY_RUSSIAN_ROUBLE      => ['kopek', 'kopeks'],
			Speller::CURRENCY_US_DOLLAR           => ['cent', 'cents'],
			Speller::CURRENCY_PL_ZLOTY            => ['grosz', 'grosze'],
			Speller::CURRENCY_TANZANIAN_SHILLING  => ['cent', 'cents'],
			Speller::CURRENCY_NORWEGIAN_KRONE     => ['øre', 'øre'],
        ];
    }

}