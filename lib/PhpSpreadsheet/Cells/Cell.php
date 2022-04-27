<?php namespace Lib\PhpSpreadsheet\Cells;
// PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\Cell as SsCell;
// Lib PhpSpreadsheet
use Lib\PhpSpreadsheet\Writer;
use Lib\PhpSpreadsheet\Styles;
use Lib\PhpSpreadsheet\DataTypes;

/**
 * Cell
 * 
 * Handles Manipulating Cell
 */
class Cell {

	/**
	 * Set Value for cell after formatting value
	 * @param  SsCell $cell
	 * @param  string $fieldType Dplus Data Type (e.g. N for numeric)
	 * @param  mixed  $value     New Cell Value
	 * @return void
	 */
	public static function setValue(SsCell $cell, string $fieldType, $value = null) {
		/** @var string $dataType PhpSpreadsheet Data Type */
		$dataType = DataTypes::getDatatype($fieldType);
		// Cleanup string value
		if ($dataType === DataTypes\Strings::TYPE) {
			$value = DataTypes\Strings::clean($value);
		}
		$cell->setValueExplicit($value, $dataType);
		
		// Set Format Code for Numbers
		if ($dataType == DataTypes\Number::TYPE) {
			$cellNumberFormat = $cell->getStyle()->getNumberFormat();
			$cellNumberFormat->setFormatCode(DataTypes\Number::generateFormatCode($value));
		}
	}

	/**
	 * Set Cell Alignment (Justify) from Dplus Field Type
	 * @param  SsCell $cell
	 * @param  string $fieldType  Dplus Data Type (e.g. N for numeric)
	 * @return void
	 */
	public static function setAlignmentFromFieldtype(SsCell $cell, $fieldType) {
		$cellAlignment = $cell->getStyle()->getAlignment();
		$cellAlignment->setHorizontal(Styles::getAlignmentCode(DataTypes::getFieldtypeJustify($fieldType)));
	}
}