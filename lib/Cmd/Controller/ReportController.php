<?php namespace Lib\Cmd\Controller;
// Dplus Reports
use Lib\Reports\Report;


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
abstract class ReportController extends JsonController {
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
	 * Initialize Report
	 * @return bool
	 */
	protected function initReport() {
		$this->report = $this->getReport();
		return empty($this->report) === false;
	}
}
