<?php namespace Lib\PhpSpreadsheet;

use PhpOffice\PhpSpreadsheet\Cell\DataType;

/**
 * DataTypes
 * 
 * Functions for converting for datatypes
 */
class DataTypes {

	/** @var array Justify codes for each fieldtype code */
	const FIELDTYPE_JUSTIFY = [
		'C' => 'left',
		'D' => 'left',
		'I' => 'right',
		'N' => 'right'
	];

	/** @var array Mapping of Fieldtype codes to Datatype codes*/
	const FIELDTYPE_DATATYPE = [
		'C' => DataType::TYPE_STRING,
		'D' => DataType::TYPE_STRING,
		'I' => DataType::TYPE_NUMERIC,
		'N' => DataType::TYPE_NUMERIC
	];

	/**
	 * Return Justify for fieldtype
	 * @param  string $type
	 * @return string
	 */
	public static function getFieldtypeJustify($type) {
		if (array_key_exists(strtoupper($type), self::FIELDTYPE_JUSTIFY) === false) {
			return 'left';
		}
		return self::FIELDTYPE_JUSTIFY[strtoupper($type)];
	}

	/**
	 * Return PhpSpreadsheet's Datatyep for field type
	 * @param  string $fieldtype
	 * @return string
	 */
	public static function getDatatype($fieldtype) {
		return static::FIELDTYPE_DATATYPE[$fieldtype];
	}
}