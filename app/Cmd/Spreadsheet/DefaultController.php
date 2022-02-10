<?php namespace App\Cmd\Spreadsheet;
// Dotenv Env Library
use Dotenv\Dotenv;
use Dotenv\RuntimeException;
// Mincli Library
use Minicli\App;
// Command Library
use Lib\Cmd\ReportController as Controller;
// Dplus Reports
use Dplus\Reports\Json\Spreadsheets\Report as Spreadsheet;
use Dplus\Reports\Json\Spreadsheets\Writer;


/**
 * Spreadsheet
 * 
 * Writes Spreadsheet from JSON report
 * 
 * @property string $lastWrittenFile  Full Filepath of the last written file
 */
class DefaultController extends Controller {
	protected $lastWrittenFile = '';

	public function handle() {
		if ($this->initConfig() === false) {
			return false;
		}
		if ($this->initEnv() === false) {
			return false;
		}
		if ($this->initReport() === false) {
			return false;
		}
		return $this->writeSpreadsheetToFile($this->createSpreadsheet());
	}

	/**
	 * Return Spreadsheet generated from Report
	 * @return Spreadsheet
	 */
	protected function createSpreadsheet() {
		$spreadsheet = new Spreadsheet();
		$spreadsheet->setJson($this->report->getJson());
		$spreadsheet->generate();
		return $spreadsheet;
	}

	/**
	 * Save Spreadsheet file
	 * @param  Spreadsheet $spreadsheet
	 * @return bool
	 */
	protected function writeSpreadsheetToFile(Spreadsheet $spreadsheet) {
		$writer = new Writer\Xlsx();
		$writer->filename   = $this->report->getId();
		$writer->fileprefix = $this->report::CODE;
		$success = $writer->write($spreadsheet->getSpreadsheet());
		if ($success === false) {
			$this->getPrinter()->error('Failed to write file: '. $writer->lastWrittenFile);
			return false;
		}
		$this->getPrinter()->success('Succeeded to write file: '. $writer->lastWrittenFile);
		$this->lastWrittenFile = $writer->lastWrittenFile;
		return true;
	}
}
