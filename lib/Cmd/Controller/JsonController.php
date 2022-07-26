<?php namespace Lib\Cmd\Controller;
// Dotenv Env Library
use Dotenv\Dotenv;
use Dotenv\RuntimeException;
// Dplus Reports
use Lib\PhpSpreadsheet\Writer;
// Lib Json
use Lib\Json\Fetcher as JsonFetcher;
use Lib\Json\Json;
// Files
use Lib\Files;
// Emails
use Lib\Email;
// Controllers
use Lib\Cmd\Controller;

/**
 * Base Class for Handling Commands that use JSON files
 */
abstract class JsonController extends Controller {

/* =============================================================
	Init Functions
============================================================= */
	/**
	 * Initialize / Load Config .env file
	 * TODO: Add Required ENV values
	 * @return bool
	 */
	protected function initConfig() {
		$config = $this->app->config;
		
		if (file_exists($config->env_dir.'/.env') === false) {
			return false;
		}
		$dotenv = Dotenv::createImmutable($config->env_dir);
		$dotenv->load();

		try {
			$dotenv->required(['DIRECTORY_JSON']);
		} catch(\RuntimeException $e) {
			return $this->error($e->getMessage);
		}
		return true;
	}

	/**
	 * Initialize Necessary $_ENV data
	 * @return bool
	 */
	protected function initEnv() {
		if ($this->hasParam('co') === false) {
			return $this->error("Please provide company number (co=CO)");
		}

		if (array_key_exists('DIRECTORY_JSON', $_ENV) === false) {
			return $this->error("'DIRECTORY_JSON' env value is not defined");
		}
		
		$companyNumber = $this->getParam('co');
		$dir = $_ENV['DIRECTORY_JSON'] . "/json$companyNumber/";

		JsonFetcher::setDir($dir);

		if (array_key_exists('SPREADSHEET_WRITE_DIR', $_ENV) === false) {
			return $this->error("'SPREADSHEET_WRITE_DIR' env value is not defined");
		}
		Writer::setDir($_ENV['SPREADSHEET_WRITE_DIR']);
		$this->initEnvTimeZone();
		return true;
	}

	/**
	 * Initialize the Local Time Zone
	 * @return bool
	 */
	protected function initEnvTimeZone() {
		$sysTZ = system('date +%Z');
		return date_default_timezone_set(timezone_name_from_abbr($sysTZ));
	}

/* =============================================================
	JSON Command Parsing
============================================================= */
	/**
	 * Copy File to location in JSON request
	 * @return bool
	 */
	protected function copyFileFromJson(Json $json) {
		if (empty($this->lastWrittenFile)) {
			return $this->error('Written File not found');
		}
		if (file_exists($this->lastWrittenFile) === false) {
			return $this->error("File '$this->lastWrittenFile' not found");
		}

		$newFile = $json->getSaveFile();

		$copier = new Files\Copier();
		$copier->setOriginalFilepath($this->lastWrittenFile);
		$copier->setDestinationDirectory($newFile->getDir());
		$copier->setDestinationFilename($newFile->filename());

		if ($copier->copy() === false) {
			return $this->error($copier->error);
		}
		$this->getPrinter()->success("Copied File: $copier->lastCopyFile");
		$this->lastWrittenFile = $copier->lastCopyFile;
		return true;
	}

	/**
	 * Send Emails parsed from the JSON Request
	 * @return bool
	 */
	protected function emailFromJson(Json $json) {
		$emails = $json->getEmails();
		
		if ($emails->hasTo() === false) {
			return true;
		}
		$this->getPrinter()->info('Sending Emails:');

		$mailer = new Email\Mailer();
		$mailer->addFile($this->lastWrittenFile);
		$errors = $mailer->mail($emails);

		foreach ($errors as $email => $msg) {
			$this->error("Error ($email): $msg");
		}
		return true;
	}
}