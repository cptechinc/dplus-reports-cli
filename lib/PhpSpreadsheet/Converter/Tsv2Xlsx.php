<?php namespace Lib\PhpSpreadsheet\Converter;
// PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
// Lib PhpSpreadsheet
use Lib\PhpSpreadsheet\Writer;
use Lib\PhpSpreadsheet\Styles;
use Lib\PhpSpreadsheet\DataTypes;

use Lib\Convert\Request;
use Lib\Convert\Json;

/**
 * Converter\Tsv2Xlsx
 * 
 * Handles Converting Cells for Xlsx from TSV
 * 
 * @property Request     $request     Request Data Container
 * @property Spreadsheet $spreadsheet Original Spreadsheet
 */
class Tsv2Xlsx {
	public function __construct(Request $request, Spreadsheet $spreadsheet) {
		$this->request = $request;
		$this->spreadsheet = $spreadsheet;
	}

	/**
	 * Convert Cell Data / Formatting for Xlsx use
	 * @return void
	 */
	public function convert() {
		$sheet = $this->spreadsheet->getActiveSheet();
		$highestColumnIndex = Coordinate::columnIndexFromString($sheet->getHighestColumn()); // e.g. 5
		Styles::setColumnsAutowidth($sheet, $highestColumnIndex);

		$fieldData = $this->request->getJson()->getFields();
		$colData = [];
		foreach ($fieldData as $field) {
			$colData[] = $field;
		}

		for ($row = 1; $row < $sheet->getHighestRow() + 1; $row++) {
			for ($col = 1; $col <= $highestColumnIndex; $col++) {
				$cell  = $sheet->getCellByColumnAndRow($col, $row);
				$value = $cell->getValue();

				if ($row == 1) {
					$cell->getStyle()->applyFromArray(Styles::STYLES_COLUMN_HEADER);
				}
				$fieldType = $colData[$col - 1]['type'];
				$cell->getStyle()->getAlignment()->setHorizontal(Styles::getAlignmentCode(DataTypes::getFieldtypeJustify($fieldType)));

				if ($row > 1) {
					$dataType = DataTypes::getDatatype($fieldType);
					
					if ($dataType === DataTypes\Strings::TYPE) {
						$value = DataTypes\Strings::clean($value);
					}
					$cell->setValueExplicit($value, $dataType);
					
					if ($fieldType == 'N') {
						$cellNumberFormat = $cell->getStyle()->getNumberFormat();
						$cellNumberFormat->setFormatCode(DataTypes\Number::generateFormatCode($value));
					}
				}
			}
		}
	}
}