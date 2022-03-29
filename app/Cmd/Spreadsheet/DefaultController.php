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
use Dplus\Reports\Files;
// Emails
use Lib\Email;

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
 *  --copy    Make Copy
 *  filename  Copy Filename
 *  dir       Copy File Directory
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
		$saved = $this->writeSpreadsheetToFile($this->createSpreadsheet());


		if ($this->hasFlag('--copy')) {
			$this->copySpreadsheetFromCmd();
		}

		if ($this->report->getJson()->getSaveFile()->hasFilename()) {
			$this->copySpreadsheetFromJson();
		}

		if ($this->report->getJson()->getEmails()->hasTo()) {
			$this->emailFromJson();
		}
		return $saved;
	}

	/**
	 * Parse Command to Copy Spreedsheet to Location
	 * @return bool
	 */
	protected function copySpreadsheetFromCmd() {
		if (empty($this->lastWrittenFile)) {
			$this->getPrinter()->error('Written File not found');
			return false;
		}
		if (file_exists($this->lastWrittenFile) === false) {
			$this->getPrinter()->error("File '$this->lastWrittenFile' not found");
			return false;
		}
		if ($this->hasParam('dir') === false || is_dir($this->getParam('dir')) === false) {
			$this->getPrinter()->error("Invalid directory provided (dir=DIR)");
			return false;
		}
		$copier = new Files\Copier();
		$copier->setOriginalFilepath($this->lastWrittenFile);
		$copier->setDestinationDirectory($this->getParam('dir'));

		if ($this->hasParam('filename')) {
			$copier->setDestinationFilename($this->getParam('filename'));
		}

		if ($copier->copy() === false) {
			$this->getPrinter()->error($copier->error);
			return false;
		}
		$this->getPrinter()->success("Copied File: $copier->lastCopyFile");
		$this->lastWrittenFile = $copier->lastCopyFile;
		return true;
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
			$this->getPrinter()->error('Failed to write file: '. $writer->lastWrittenFile);
			return false;
		}
		$this->getPrinter()->success('Succeeded to write file: '. $writer->lastWrittenFile);
		$this->lastWrittenFile = $writer->lastWrittenFile;
		return true;
	}

	/**
	 * Send Emails parsed from the JSON Request
	 * @return bool
	 */
	private function emailFromJson() {
		$emails = $this->report->getJson()->getEmails();
		
		if ($emails->hasTo() === false) {
			return true;
		}
		$this->getPrinter()->info('Sending Emails:');

		$mailer = new Email\Mailer();
		$mailer->addFile($this->lastWrittenFile);
		$errors = $mailer->mail($emails);

		foreach ($errors as $email => $msg) {
			$this->getPrinter()->error("Error ($email): $msg");
		}
		return true;
	}

	/**
	 * Copy Spreadsheet to location in JSON request
	 * @return bool
	 */
	private function copySpreadsheetFromJson() {
		if (empty($this->lastWrittenFile)) {
			$this->getPrinter()->error('Written File not found');
			return false;
		}
		if (file_exists($this->lastWrittenFile) === false) {
			$this->getPrinter()->error("File '$this->lastWrittenFile' not found");
			return false;
		}

		$newFile = $this->report->getJson()->getSaveFile();

		$copier = new Files\Copier();
		$copier->setOriginalFilepath($this->lastWrittenFile);
		$copier->setDestinationDirectory($newFile->getDir());
		$copier->setDestinationFilename($newFile->filename());

		if ($copier->copy() === false) {
			$this->getPrinter()->error($copier->error);
			return false;
		}
		$this->getPrinter()->success("Copied File: $copier->lastCopyFile");
		$this->lastWrittenFile = $copier->lastCopyFile;
		return true;
	}
}
