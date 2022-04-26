<?php namespace Lib\PhpSpreadsheet;
// PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style as SpreadsheetStyles;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

/**
 * Styles
 * 
 * Handles Adding styling to Spreadsheet
 */
class Styles {
	/** @var array Column Heading Styles */
	const STYLES_COLUMN_HEADER = [
		'font' => [
			'bold' => true,
			'size' => 14
		],
		'borders' => [
			'bottom' => [
				'borderStyle' => SpreadsheetStyles\Border::BORDER_THICK,
			],
		],
	];


	/** @var array Record Heading Styles */
	const STYLES_RECORD_HEADING = [
		'font' => [
			'bold' => true,
			'size' => 12
		],
		'borders' => [
			'top' => [
				'borderStyle' => SpreadsheetStyles\Border::BORDER_THIN,
			],
		],
		'fill' => [
			'fillType' => SpreadsheetStyles\Fill::FILL_SOLID,
			'startColor' => [
				'rgb' => 'E6E6EA',
			],
			'endColor' => [
				'rgb' => 'E6E6EA',
			],
		],
	];


	/**
	 * Returns Spreadsheet Alignment Code
	 * @param  string $justify  Code given e.g. r, right
	 * @return string
	 */
	public static function getAlignmentCode($justify) {
		switch (substr($justify, 0, 1)) {
			case 'r':
				return Alignment::HORIZONTAL_RIGHT;
				break;
			case 'c':
				return Alignment::HORIZONTAL_CENTER;
				break;
			default:
				return Alignment::HORIZONTAL_LEFT;
				break;
		}
	}

	/**
	 * Set Columns to be autowidth
	 * @param Worksheet $sheet       Sheet
	 * @param int       $columncount Number of Columns to iterate
	 */
	public static function setColumnsAutowidth(Worksheet $sheet, int $columncount) {
		if ($columncount === 0) {
			$columncount = Coordinate::columnIndexFromString($sheet->getHighestColumn());
		}

		for ($i = 0; $i < ($columncount); $i++) {
			$index = Coordinate::stringFromColumnIndex($i);
			$sheet->getColumnDimension($index)->setAutoSize(true);
		}
	}

}