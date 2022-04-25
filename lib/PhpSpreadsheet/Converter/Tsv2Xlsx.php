<?php namespace Lib\PhpSpreadsheet\Converter;
// PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
// Dplus Reports
use Dplus\Reports\Json\Spreadsheets\Report as ReportSpreadsheet;

/**
 * Converter\Tsv2Xlsx
 * 
 * Handles Converting Cells for Xlsx from TSV
 */
class Tsv2Xlsx {
	public function __construct(Spreadsheet $spreadsheet) {
		$this->spreadsheet = $spreadsheet;
	}

	/**
	 * Convert Cell Data / Formatting for Xlsx use
	 * @return void
	 */
	public function convert() {
		$sheet = $this->spreadsheet->getActiveSheet();
		$highestColumnIndex = Coordinate::columnIndexFromString($sheet->getHighestColumn()); // e.g. 5

		for ($row = 1; $row < $sheet->getHighestRow(); $row++) {
			for ($col = 1; $col <= $highestColumnIndex; $col++) {
				$cell  = $sheet->getCellByColumnAndRow($col, $row);
				$value = $cell->getValue();

				if ($row == 1) {
					$cell->getStyle()->applyFromArray(ReportSpreadsheet::STYLES_COLUMN_HEADER);
				}
				$cell->setValueExplicit($value, DataType::TYPE_STRING);
			}
		}
	}
}