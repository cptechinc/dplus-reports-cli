<?php namespace Lib\PhpSpreadsheet;

use PhpOffice\PhpSpreadsheet\Style as SpreadsheetStyles;

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

}