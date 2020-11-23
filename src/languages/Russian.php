<?php
namespace js\tools\numbers2words\languages;

use js\tools\numbers2words\exceptions\InvalidArgumentException;
use js\tools\numbers2words\Speller;

final class Russian extends Speller
{
	protected $minus = 'минус';
	protected $decimalSeparator = ' и ';
	
	protected function spellHundred(int $number, int $groupOfThrees, bool $isDecimalPart, string $currency): string
	{
		static $hundreds = [
			1 => 'сто',
			2 => 'двести',
			3 => 'триста',
			4 => 'четыреста',
			5 => 'пятьсот',
			6 => 'шестьсот',
			7 => 'семьсот',
			8 => 'восемьсот',
			9 => 'девятьсот',
		];
		static $tens = [
			1 => 'десять',
			2 => 'двадцать',
			3 => 'тридцать',
			4 => 'сорок',
			5 => 'пятьдесят',
			6 => 'шестьдесят',
			7 => 'семьдесят',
			8 => 'восемьдесят',
			9 => 'девяносто',
		];
		static $teens = [
			11 => 'одиннадцать',
			12 => 'двенадцать',
			13 => 'тринадцать',
			14 => 'четырнадцать',
			15 => 'пятнадцать',
			16 => 'шестнадцать',
			17 => 'семнадцать',
			18 => 'восемнадцать',
			19 => 'девятнадцать',
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
			$text .= $this->spellSingle($number, $groupOfThrees, $isDecimalPart, $currency);
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
				$text .= ' ' . $this->spellSingle($number % 10, $groupOfThrees, $isDecimalPart, $currency);
			}
		}
		
		return $text;
	}
	
	private function spellSingle(int $digit, int $groupOfThrees, bool $isDecimalPart, string $currency): string
	{
		static $singlesMasculine = [
			0 => 'ноль',
			1 => 'один',
			2 => 'два',
			3 => 'три',
			4 => 'четыре',
			5 => 'пять',
			6 => 'шесть',
			7 => 'семь',
			8 => 'восемь',
			9 => 'девять',
		];
		static $singlesFeminine = [
			0 => 'ноль',
			1 => 'одна',
			2 => 'две',
			3 => 'три',
			4 => 'четыре',
			5 => 'пять',
			6 => 'шесть',
			7 => 'семь',
			8 => 'восемь',
			9 => 'девять',
		];
		
		if (($groupOfThrees === 2) // thousands
			|| ($isDecimalPart && ($currency === self::CURRENCY_RUSSIAN_ROUBLE))) // russian kopeks
		{
			return $singlesFeminine[$digit];
		}
		
		return $singlesMasculine[$digit];
	}
	
	protected function spellExponent(string $type, int $number, string $currency): string
	{
		$tens = $number % 100;
		$singles = $number % 10;
		
		if ($type === 'million')
		{
			if (($singles === 1) && ($tens !== 11)) // 1, 21, ... 91
			{
				return 'миллион';
			}
			
			if ((($singles > 1) && ($singles < 5)) // 2-4, 22-24 ... 92-94
				&& (($tens - $singles) !== 10))
			{
				return 'миллиона';
			}
			
			return 'миллионов';
		}
		
		if ($type === 'thousand')
		{
			if (($singles === 1) && ($tens !== 11)) // 1, 21, ... 91
			{
				return 'тысяча';
			}
			
			if ((($singles > 1) && ($singles < 5)) // 2-4, 22-24 ... 92-94
				&& (($tens - $singles) !== 10))
			{
				return 'тысячи';
			}
			
			return 'тысяч';
		}
		
		return '';
	}
	
	protected function getCurrencyNameMajor(int $amount, string $currency): string
	{
		static $names = [
			self::CURRENCY_EURO           => ['евро', 'евро', 'евро'],
			self::CURRENCY_BRITISH_POUND  => ['фунт', 'фунта', 'фунтов'],
			self::CURRENCY_LATVIAN_LAT    => ['лат', 'лата', 'латов'],
			self::CURRENCY_LITHUANIAN_LIT => ['лит', 'лита', 'литов'],
			self::CURRENCY_RUSSIAN_ROUBLE => ['рубль', 'рубля', 'рублей'],
			self::CURRENCY_US_DOLLAR      => ['доллар', 'доллара', 'долларов'],
			self::CURRENCY_PL_ZLOTY       => ['зло́тый', 'злота', 'злотых'],
		];
		
		return self::getCurrencyName($names, $amount, $currency);
	}
	
	protected function getCurrencyNameMinor(int $amount, string $currency): string
	{
		static $names = [
			self::CURRENCY_EURO           => ['цент', 'цента', 'центов'],
			self::CURRENCY_BRITISH_POUND  => ['пенни', 'пенса', 'пенсов'],
			self::CURRENCY_LATVIAN_LAT    => ['сантим', 'сантима', 'сантимов'],
			self::CURRENCY_LITHUANIAN_LIT => ['цент', 'цента', 'центов'],
			self::CURRENCY_RUSSIAN_ROUBLE => ['копейка', 'копейки', 'копеек'],
			self::CURRENCY_US_DOLLAR      => ['цент', 'цента', 'центов'],
			self::CURRENCY_PL_ZLOTY       => ['грош', 'гроша', 'грошей'],
		];
		
		return self::getCurrencyName($names, $amount, $currency);
	}
	
	private static function getCurrencyName(array $names, int $amount, string $currency): string
	{
		$tens = $amount % 100;
		$singles = $amount % 10;
		
		if (($singles === 1) && ($tens !== 11)) // 1, 21, ... 91
		{
			$index = 0;
		}
		else if ((($singles > 1) && ($singles < 5)) // 2-4, 22-24, ... 92-94
			&& (($tens - $singles) !== 10))
		{
			$index = 1;
		}
		else // 0, 5, 6, 7, 8, 9, 11-19, 10, 20, 30...90
		{
			$index = 2;
		}
		
		return $names[$currency][$index] ?? self::throw(new InvalidArgumentException('Unsupported currency'));
	}
}
