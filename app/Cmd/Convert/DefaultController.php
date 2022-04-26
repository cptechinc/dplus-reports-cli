<?php namespace App\Cmd\Convert;
// PhpSpreadsheet Library
use PhpOffice\PhpSpreadsheet\Spreadsheet as PhpSpreadsheetSpreadsheet;
// Dplus Report
use Lib\Convert\Request;
// Lib PhpSpreadsheet
use Lib\PhpSpreadsheet\Writer;
use Lib\PhpSpreadsheet\Reader;
use Lib\PhpSpreadsheet\Converter;
// Command Library
use Lib\Cmd\Controller\ReportController as Controller;

/**
 * Spreadsheet
 * Writes Spreadsheet from JSON report
 * 
 * @property string $lastWrittenFile  Full Filepath of the last written file
 * 
 * Usage:
 *   [shell] spreadsheet [options]
 * Options:
 *   report   Report Code
 *   id       Report ID
 */
class DefaultController extends Controller {
	const SAVE_EXTENSION = 'xlsx';
	protected $lastWrittenFile = '';

	public function handle() {
		if ($this->initConfig() === false) {
			return false;
		}
		if ($this->initEnv() === false) {
			return false;
		}
		$this->logCommand();
		
		if ($this->initRequest() === false) {
			return false;
		}

		if ($this->convert() === false) {
			return false;
		}

		if ($this->request->getJson()->getSaveFile()->hasFilename()) {
			$this->copyFileFromJson($this->request->getJson());
		}

		if ($this->hasFlag('--skip-email')) {
			return true;
		}

		if ($this->request->getJson()->getEmails()->hasTo()) {
			$this->emailFromJson($this->request->getJson());
		}
		return true;
	}

	/**
	 * Return Request
	 * @return Request
	 */
	protected function getRequest() {
		if ($this->hasParam('report') === false || $this->hasParam('id') === false) {
			return $this->error('Please provide report code (report=REPORT) and id (id=ID)');
		}
		$request = new Request();
		$request->setCode($this->getParam('report'));
		$request->setId($this->getParam('id'));

		if ($request->fetch() === false) {
			return $this->error($request->errorMsg);
		}
		return $request;
	}

	/**
	 * Initialize Request
	 * @return bool
	 */
	protected function initRequest() {
		$this->request = $this->getRequest();
		return empty($this->request) === false;
	}

	public function convert() {
		if (file_exists($this->request->getJson()->getSrcFilepath()) === false) {
			return $this->error('Failed to read file: '. $this->request->getJson()->getSrcFilepath());
		}

		$spreadsheet = Reader\Tsv::getSpreadsheet($this->request->getJson()->getSrcFilepath());
		return $this->writeSpreadsheet($spreadsheet);
	}

	protected function writeSpreadsheet(PhpSpreadsheetSpreadsheet $spreadsheet) {
		$converter = new Converter\Tsv2Xlsx($spreadsheet);
		$converter->convert();

		$writer = new Writer\Xlsx($spreadsheet);
		$writer->filename   = $this->request->getId();
		$writer->fileprefix = $this->request->getCode();
		$success = $writer->write($spreadsheet);
		
		if ($success === false) {
			return $this->error('Failed to write file: '. $writer->lastWrittenFile);
		}
		$this->getPrinter()->success('Succeeded to write file: '. $writer->lastWrittenFile);
		$this->lastWrittenFile = $writer->lastWrittenFile;
		return true;
	}
}
