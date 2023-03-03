<?php namespace Lib\Cmd\Controller;
// Cache
use Cache\Adapter\Apcu\ApcuCachePool;
use Cache\Bridge\SimpleCache\SimpleCacheBridge;
// PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Settings as PhpSpreadsheetSettings;
use Lib\Files;
// Dplus Reports
use Lib\Reports\Report;


/**
 * Report Controller
 * 
 * Base Class for Reports Manipulation
 * @property int    $startTime        Unix Timestamp of when Conversion started
 * 
 * Usage:
 *   [shell] [argument] [options]
 * Options:
 * 
 * @property Report $report Report
 */
abstract class ReportController extends JsonController {
	protected $report;
	protected $startTime = 0;
	protected $lastWrittenFile = '';

	public function handle() {
		return $this->init();
	}

/* =============================================================
	Init Functions
============================================================= */
	protected function init() {
		$this->startTime = time();
		$this->logCommand();

		if ($this->initConfig() === false) {
			return false;
		}
		if ($this->initEnv() === false) {
			return false;
		}
		$this->initIni(); 
		$this->initSpreadsheetCache();
		return true;
	}

	/**
	 * Set php.ini settings
	 * @return void
	 */
	protected function initIni() {
		$settings = parse_ini_file($this->app->config->env_dir.'/php.ini');

		foreach ($settings as $option => $value) {
			ini_set($option, $value);
		}
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
	 * Initialize Cache for Spreadsheet
	 * @return bool
	 */
	protected function initSpreadsheetCache() {
		$pool = new ApcuCachePool();
		$simpleCache = new SimpleCacheBridge($pool);
		PhpSpreadsheetSettings::setCache($simpleCache);
		return true;
	}

/* =============================================================
	Init Report Functions
============================================================= */
	/**
	 * Return Report
	 * @return Report
	 */
	protected function _getReportFromParam() {
		/** @var string */
		$name = $this->getParam('report');
		$ns = "Lib\\Reports\\Report\\";
		$class = $ns.ucfirst($name);
		
		if (class_exists($class) === false) {
			$class = "Lib\\Reports\\Report";
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
			return $this->error('Please provide report code (report=REPORT) and id (id=ID)');
		}
		$report = $this->_getReportFromParam();
		if (empty($report)) {
			return false;
		}
		$report->setId($this->getParam('id'));

		if ($report->fetch() === false) {
			return $this->error($report->errorMsg);
		}
		return $report;
	}

	/**
	 * Parse Command to Copy Spreedsheet to Location
	 * @return bool
	 */
	protected function copySpreadsheetFromCmd() {
		if (empty($this->lastWrittenFile)) {
			return $this->error('Written File not found');
		}
		if (file_exists($this->lastWrittenFile) === false) {
			return $this->error("File '$this->lastWrittenFile' not found");
		}
		if ($this->hasParam('dir') === false || is_dir($this->getParam('dir')) === false) {
			return $this->error("Invalid directory provided (dir=DIR)");
		}
		$copier = new Files\Copier();
		$copier->setOriginalFilepath($this->lastWrittenFile);
		$copier->setDestinationDirectory($this->getParam('dir'));

		if ($this->hasParam('filename')) {
			$copier->setDestinationFilename($this->getParam('filename'));
		}

		if ($copier->copy() === false) {
			return $this->error($copier->error);
		}
		$this->getPrinter()->success("Copied File: $copier->lastCopyFile");
		$this->lastWrittenFile = $copier->lastCopyFile;
		return true;
	}

	/**
	 * Display Elapsed Time
	 * @return void
	 */
	protected function displayElapsedTime() {
		if (empty($this->startTime)) {
			$this->getPrinter()->info('nope');
			return false;
		}
		$endTime = time();
		$elapsedTime = $endTime - $this->startTime;

		if ($this->hasFlag('--debug')) {
			$this->getPrinter()->info("Time Elapsed: " . $elapsedTime  . ' seconds');
		}
	}
}
