<?php namespace Lib\PhpSpreadsheet;
// PHP Spreadsheet Library
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

/**
 * StringValueBinder
 * 
 * Handles Binding Cell Data as string
 */
class StringValueBinder extends DefaultValueBinder {
	/**
	 * DataType for value.
	 * @param  mixed   $pValue
	 * @return string
	 */
	public static function dataTypeForValue($pValue) {
		return DataType::TYPE_STRING;
	}
}