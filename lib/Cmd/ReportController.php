<?php namespace Lib\Cmd;
// Dotenv Env Library
use Dotenv\Dotenv;
use Dotenv\RuntimeException;
// Mincli Library
use Minicli\App;
// Command Library
use Pauldro\Minicli\Cmd\Controller;
// Dplus Reports
use Dplus\Reports\Json\Fetcher as JsonFetcher;
use Dplus\Reports\Json\Report;
use Dplus\Reports\Json\Spreadsheets\Writer;


/**
 * Report Controller
 * 
 * Base Class for Reports Manipulation
 */
abstract class ReportController extends Controller {

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
		return true;
	}

	/**
	 * Return Report
	 * @return Report
	 */
	protected function _getReportFromParam() {
		/** @var string */
		$name = $this->getParam('report');
		$ns = "Dplus\\Reports\\Json\\Report\\";
		$class = $ns.ucfirst($name);
		
		if (class_exists($class) === false) {
			$this->getPrinter()->error("Report '$name' not found");
			return false;
		}
		$report = new $class();
		return $report;
	}

	/**
	 * Return Report
	 * @return Report
	 */
	protected function getReport() {
		if ($this->hasParam('report') === false || $this->hasParam('id') === false) {
			$this->getPrinter()->error("Please provide report code (report=REPORT) and id (id=ID)");
			return false;
		}
		$report = $this->_getReportFromParam();
		if (empty($report)) {
			return false;
		}
		$report->setId($this->getParam('id'));

		if ($report->fetch() === false) {
			$this->getPrinter()->error($report->errorMsg);
			return false;
		}
		return $report;
	}

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
			$this->getPrinter()->error($e->getMessage);
			return false;
		}
		return true;
	}

	/**
	 * Initialize Necessary $_ENV data
	 * @return bool
	 */
	protected function initEnv() {
		if (array_key_exists('DIRECTORY_JSON', $_ENV) === false) {
			$this->getPrinter()->error("'DIRECTORY_JSON' env value is not defined");
			return false;
		}
		JsonFetcher::setDir($_ENV['DIRECTORY_JSON']);

		if (array_key_exists('SPREADSHEET_WRITE_DIR', $_ENV) === false) {
			$this->getPrinter()->error("'SPREADSHEET_WRITE_DIR' env value is not defined");
			return false;
		}
		Writer::setDir($_ENV['SPREADSHEET_WRITE_DIR']);
		return true;
	}
}
