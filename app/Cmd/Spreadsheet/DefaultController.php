<?php namespace App\Cmd\Spreadsheet;
// Dotenv Env Library
use Dotenv\Dotenv;
use Dotenv\RuntimeException;
// Mincli Library
use Minicli\App;
// Command Library
use Lib\Cmd\ReportController as Controller;
// Dplus Reports
use Dplus\Reports\Json\Report;
use Dplus\Reports\Json\Spreadsheets\Report as Spreadsheet;
use Dplus\Reports\Json\Spreadsheets\Writer;


/**
 * Spreadsheet
 * 
 * Writes Spreadsheet from JSON report
 */
class DefaultController extends Controller {
	public function handle() {
		if ($this->initConfig() === false) {
			return false;
		}
		if ($this->initEnv() === false) {
			return false;
		}
		$report = $this->getReport();

		if ($report === false) {
			return false;
		}
		$spreadsheet = $this->createSpreadsheet($report);
		return $this->writeSpreadsheetToFile($report, $spreadsheet);
	}

	/**
	 * Return Spreadsheet generated from Report
	 * @param  Report       $report
	 * @return Spreadsheet
	 */
	protected function createSpreadsheet(Report $report) {
		$spreadsheet = new Spreadsheet();
		$spreadsheet->setJson($report->getJson());
		$spreadsheet->generate();
		return $spreadsheet;
	}

	/**
	 * Save Spreadsheet file
	 * @param  Report      $report
	 * @param  Spreadsheet $spreadsheet
	 * @return bool
	 */
	protected function writeSpreadsheetToFile(Report $report, Spreadsheet $spreadsheet) {
		$writer = new Writer\Xlsx();
		$writer->filename   = $report->getId();
		$writer->fileprefix = $report::CODE;
		$success = $writer->write($spreadsheet->getSpreadsheet());
		if ($success === false) {
			$this->getPrinter()->error('Failed to write file: '. $writer->lastWrittenFile);
			return false;
		}
		$this->getPrinter()->success('Succeeded to write file: '. $writer->lastWrittenFile);
		return true;
	}

	
}
