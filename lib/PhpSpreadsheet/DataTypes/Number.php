<?php namespace Lib\PhpSpreadsheet\DataTypes;
// PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


/**
 * Number
 * 
 * Functions for Numbers
 */
class Number {
	const TYPE = DataType::TYPE_NUMERIC;
	const TYPE_DPLUS = 'N';
	
	/**
	 * Return Format Code from number
	 * @param  float|int|string $nbr
	 * @return string
	 */
	public static function generateFormatCode($nbr) {
		$nbr = "$nbr";
		$formatCode = rtrim(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, '.00');
		$decimalFormatCode  = self::generateDecimalFormatCode($nbr);
		return $formatCode . $decimalFormatCode;
	}

	/**
	 * Return Format Code for Decimal Seperator
	 * @param  float|int|string $nbr
	 * @return string
	 */
	public static function generateDecimalFormatCode($nbr) {
		$nbr = "$nbr";

		if (strpos($nbr, '.') !== false) {
			$parts = explode('.', $nbr);
			$beforeDecimal = str_pad("", strlen($parts[0]), "0");
			$afterDecimal = str_pad("", strlen($parts[1]), "0");
			$formatCode = "." . $afterDecimal;
			return $formatCode;
		}
		return '';
	}
}