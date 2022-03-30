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
 * 
 * Usage:
 *   [shell] [argument] [options]
 * Options:
 * 
 * @property Report $report Report
 */
abstract class ReportController extends Controller {
	protected $report;

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
			$class = "Dplus\\Reports\\Json\\Report";
			$report = new $class();
			$report->setCode($name);
			return $report;
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
	 * Initialize Report
	 * @return bool
	 */
	protected function initReport() {
		$this->report = $this->getReport();
		return empty($this->report) === false;
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
		if ($this->hasParam('co') === false) {
			$this->getPrinter()->error("Please provide company number (co=CO)");
			return false;
		}

		if (array_key_exists('DIRECTORY_JSON', $_ENV) === false) {
			$this->getPrinter()->error("'DIRECTORY_JSON' env value is not defined");
			return false;
		}
		
		$companyNumber = $this->getParam('co');
		$dir = $_ENV['DIRECTORY_JSON'] . "/json$companyNumber/";

		JsonFetcher::setDir($dir);

		if (array_key_exists('SPREADSHEET_WRITE_DIR', $_ENV) === false) {
			$this->getPrinter()->error("'SPREADSHEET_WRITE_DIR' env value is not defined");
			return false;
		}
		Writer::setDir($_ENV['SPREADSHEET_WRITE_DIR']);
		return true;
	}

	/**
	 * Log Command sent to App
	 * @return void
	 */
	protected function logCommand() {
		$logCommands = boolval($_ENV['LOG_COMMANDS']);

		if ($logCommands === false) {
			return true;
		}
		$file = $this->app->config->env_dir . '/rqstlog';
		$cmd = implode(' ', $this->input->getRawArgs());
		$parts = [date('Ymd'), date('His'), $cmd];
		$line = implode("\t", $parts) . PHP_EOL;
		file_put_contents($file, $line);
	}
}
