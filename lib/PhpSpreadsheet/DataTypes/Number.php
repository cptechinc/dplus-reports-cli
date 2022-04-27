<?php namespace Lib\PhpSpreadsheet\DataTypes;

/**
 * Number
 * 
 * Functions for Numbers
 */
class Number {
	/**
	 * Return Format Code from number
	 * @param  string|float $nbr
	 * @return string
	 */
	public static function generateFormatCode($nbr) {
		$nbr = "$nbr";
		$formatCode = "0";
		
		if (strpos($nbr, '.') !== false) {
			$parts = explode('.', $nbr);
			$beforeDecimal = str_pad("", strlen($parts[0]), "0");
			$afterDecimal = str_pad("", strlen($parts[1]), "0");
			$formatCode = $beforeDecimal . "." . $afterDecimal;
		}
		return $formatCode;
	}
}