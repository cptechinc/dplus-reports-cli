<?php namespace App\Cmd\Log;
// Command Library
use Lib\Cmd\Controller\ReportController as Controller;

/**
 * Log\Clear
 * 
 * Provides Clearing of the Log files
 * 
 * Usage:
 *   [shell] log clear [options]
 * Options:
 *   log=LOG log name *** Optional ***
 */
class ClearController extends Controller {

	public function handle() {
		if ($this->hasParam('log') === false) {
			return $this->clearAllLogs();
		}
		$this->handleNamedLogClear();
	}

	/**
	 * Empty specific log
	 * @return bool
	 */
	private function handleNamedLogClear() {
		$name = $this->getParam('log');

		switch ($name) {
			case 'commands':
			case 'command':
			case 'cmd':
				return $this->clearCmdLog();
				break;
			case 'error':
				return $this->clearErrorLog();
				break;
			default: 
				return false;
				break;
		}
	}

	/**
	 * Empty All Logs
	 * @return bool
	 */
	private function clearAllLogs() {
		$this->clearCmdLog();
		$this->clearErrorLog();
		return true;
	}

	/**
	 * Empty Command Log
	 * @return bool
	 */
	private function clearCmdLog() {
		$file = $this->getLogCmdFilePath();

		if (file_exists($file)) {
			return file_put_contents($file, '');
		}
		return true;
	}

	/**
	 * Empty Error Log
	 * @return bool
	 */
	private function clearErrorLog() {
		$file = $this->getLogErrorFilePath();

		if (file_exists($file)) {
			return file_put_contents($file, '');
		}
		return true;
	}
}
