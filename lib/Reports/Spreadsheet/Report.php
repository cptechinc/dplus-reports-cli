<?php namespace Lib\Reports\Spreadsheet;
// PhpSpreadsheet Library
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
// Reports Library
use Lib\Reports\Json;
// Lib PhpSpreadsheets
use Lib\PhpSpreadsheet\Writer;
use Lib\PhpSpreadsheet\Styles;


/**
 * Spreadsheets\Report
 * Creates and populates Spreadsheet class
 * 
 * @property Spreadsheet $spreadsheet Spreadsheet Data
 * @property Json        $json        JSON Data Container
 */
class Report {
	protected $spreadsheet;
	protected $json;

	/** @var array Mapping of Fieldtype codes to Datatype codes*/
	const FIELDTYPE_DATATYPE = [
		'C' => DataType::TYPE_STRING,
		'D' => DataType::TYPE_STRING,
		'I' => DataType::TYPE_NUMERIC,
		'N' => DataType::TYPE_NUMERIC
	];

	public function __construct() {
		$this->spreadsheet = new Spreadsheet();
	}

	/**
	 * Set Json
	 * @param  Json $json  JSON Container
	 * @return void
	 */
	public function setJson(Json $json) {
		$this->json = $json;
	}

	/**
	 * Populate Spreadsheet
	 * @return void
	 */
	public function generate() {
		$this->generateHeader();
		$this->generateBody();
	}

	/**
	 * Return Spreadsheet
	 * @return Spreadsheet
	 */
	public function getSpreadsheet() {
		return $this->spreadsheet;
	}

	/**
	 * Populates Column Headers in the Spreadsheet
	 * @return void
	 */
	protected function generateHeader() {
		$sheet = $this->spreadsheet->getActiveSheet();
		$colCount = count($this->json->getFields());
		Styles::setColumnsAutowidth($sheet, $colCount);

		$row = 1;
		// If Report has headers, push columns to the right 
		$i   = $this->json->hasHeaders() ? 2 : 1;
		
		foreach ($this->json->getFields() as $key => $field) {
			$cell = $sheet->getCellByColumnAndRow($i, $row);
			$cell->getStyle()->applyFromArray(Styles::STYLES_COLUMN_HEADER);
			$cell->getStyle()->getAlignment()->setHorizontal(Styles::getAlignmentCode($this->json->getFieldJustify($key)));
			$cell->setValue($field['label']);
			$i++;
		}
		// Freeze Column Heading Rows
		$sheet->freezePane('A2'); 
	}

	/**
	 * Populate Spreadsheet with Report Data
	 * @return void
	 */
	protected function generateBody() {
		$sheet = $this->spreadsheet->getActiveSheet();
		$row   = $this->getNewRowIndex();
		$data  = $this->json->getData();
		$colCount = count($this->json->getFields());

		foreach ($data as $record) {
			if (array_key_exists('header', $record)) {
				$cell = $sheet->getCellByColumnAndRow(1, $row);
				$cell->getStyle()->applyFromArray(Styles::STYLES_RECORD_HEADING);
				$cell->setValue($record['header']);

				$c1 = Coordinate::stringFromColumnIndex(1) . $row;
				$c2 = Coordinate::stringFromColumnIndex($colCount + 1) . $row;
				$sheet->mergeCells("$c1:$c2");
				$row++;
			}

			if (array_key_exists('detail', $record)) {
				foreach ($record['detail'] as $detail) {
					// If Report has headers, push columns to the right 
					$i = $this->json->hasHeaders() ? 2 : 1;
	
					foreach ($this->json->getFields() as $key => $field) {
						$cell = $sheet->getCellByColumnAndRow($i, $row);
						$cell->getStyle()->getAlignment()->setHorizontal(Styles::getAlignmentCode($this->json->getFieldJustify($key)));
						$cell->setValueExplicit(str_replace('\\', '', $detail[$key]), static::FIELDTYPE_DATATYPE[$field['type']]);
						$i++;
					}
					$row++;
				}
			}
		}
	}

	/**
	 * Return Index for the next available blank row
	 * @param  Worksheet $sheet
	 * @return int
	 */
	protected function getNewRowIndex(Worksheet $sheet = null) {
		$sheet = $sheet ? $sheet : $this->spreadsheet->getActiveSheet();
		return $sheet->getHighestRow() + 1;
	}
}
