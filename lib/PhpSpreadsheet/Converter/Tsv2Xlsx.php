<?php namespace Lib\PhpSpreadsheet\Converter;
// PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
// Lib PhpSpreadsheet
use Lib\PhpSpreadsheet\Styles;
use Lib\PhpSpreadsheet\Cells\Cell;
// Lib Convert
use Lib\Convert\Request;

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

				if ($row == 1) {
					$cell->getStyle()->applyFromArray(Styles::STYLES_COLUMN_HEADER);
				}
				/** @var string $fieldType Dplus Data Type */
				$fieldType = $colData[$col - 1]['type'];
				Cell::setAlignmentFromFieldtype($cell, $fieldType);
				
				if ($row > 1) {
					Cell::setValue($cell, $fieldType, $cell->getValue());
				}
			}
		}
		// Freeze Column Heading Rows
		$sheet->freezePane('A2'); 
	}
}