<?php namespace Lib\PhpSpreadsheet\DataTypes;
// PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Cell\DataType;

/**
 * String
 * 
 * Functions for Strings
 */
class Strings {
	const TYPE = DataType::TYPE_STRING;
	
	public static function clean($str) {
		return stripslashes($str);
	}
}