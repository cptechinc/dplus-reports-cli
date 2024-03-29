<?php namespace App\Cmd\Spreadsheet;
// Dotenv Env Library
use Dotenv\Dotenv;
use Dotenv\RuntimeException;
// Mincli Library
use Minicli\App;
// Command Library
use Lib\Cmd\Controller\ReportController as Controller;
// Lib Reports
use Lib\Reports\Spreadsheet\Report as Spreadsheet;
use Lib\PhpSpreadsheet\Writer;
// Lib Files
use Lib\Files;

/**
 * Spreadsheet
 * Writes Spreadsheet from JSON report
 * 
 * @property string $lastWrittenFile  Full Filepath of the last written file
 * @property int    $startTime        Unix Timestamp of when Conversion started
 * 
 * Usage:
 *   [shell] spreadsheet [options]
 * Options:
 *   report   Report Code
 *   id       Report ID
 *  --copy    Make Copy
 *  filename  Copy Filename
 *  dir       Copy File Directory
 * --debug    Run and display debug
 */
class DefaultController extends Controller {
	protected $lastWrittenFile = '';

	public function handle() {
		if ($this->init() === false) {
			return false;
		}
		
		if ($this->initReport() === false) {
			return false;
		}
		$saved = $this->writeSpreadsheetToFile($this->createSpreadsheet());

		if ($saved === false) {
			return false;
		}

		$this->displayElapsedTime();

		if ($this->hasFlag('--copy')) {
			$this->copySpreadsheetFromCmd();
		}

		if ($this->report->getJson()->getSaveFile()->hasFilename()) {
			$this->copyFileFromJson($this->report->getJson());
		}

		if ($this->hasFlag('--skip-email')) {
			return $saved;
		}

		if ($this->report->getJson()->getEmails()->hasTo()) {
			$this->emailFromJson($this->report->getJson());
		}
		return $saved;
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
		$writer->fileprefix = $this->report->getCode();
		$success = $writer->write($spreadsheet->getSpreadsheet());

		if ($success === false) {
			return $this->error('Failed to write file: '. $writer->lastWrittenFile);
		}
		if ($this->hasFlag('--debug')) {
			$this->getPrinter()->success('Succeeded to write file: '. $writer->lastWrittenFile);
		}
		$this->lastWrittenFile = $writer->lastWrittenFile;
		return true;
	}
}
