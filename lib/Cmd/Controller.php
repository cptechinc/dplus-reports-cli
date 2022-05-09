<?php namespace Lib\Cmd;
// Command Library
use Pauldro\Minicli\Cmd\Controller as Base;


/**
 * Controller
 * 
 * Base Class for Command Handling
 * 
 * Usage:
 *   [shell] [argument] [options]
 * Options:
 */
abstract class Controller extends Base {
	const LOG_CMD_NAME   = 'commands.log';
	const LOG_ERROR_NAME = 'error.log';

	/**
	 * Return the Filepath to the command log
	 * @return string
	 */
	protected function getLogCmdFilePath() {
		return $this->app->config->log_dir . '/' . static::LOG_CMD_NAME;
	}

	/**
	 * Return the Filepath to the error log
	 * @return string
	 */
	protected function getLogErrorFilePath() {
		return $this->app->config->log_dir . '/' . static::LOG_ERROR_NAME;
	}
	
	/**
	 * Log Command sent to App
	 * @return void
	 */
	protected function logCommand() {
		if (array_key_exists('LOG_COMMANDS', $_ENV) === false || boolval($_ENV['LOG_COMMANDS']) === false) {
			return true;
		}
		$file = $this->getLogCmdFilePath();
		$cmd  = implode(' ', $this->input->getRawArgs());
		$line = implode("\t", [date('Ymd'), date('His'), $cmd]) . PHP_EOL;
		$fileContent = '';

		if (file_exists($file)) {
			$fileContent = file_get_contents($file);
		}
		file_put_contents($file, $fileContent . $line);
	}

	/**
	 * Log Command sent to App
	 * @return void
	 */
	protected function logError($msg) {
		if (array_key_exists('LOG_ERRORS', $_ENV) === false || boolval($_ENV['LOG_ERRORS']) === false) {
			return true;
		}
		$file = $this->getLogErrorFilePath();
		$cmd  = implode(' ', $this->input->getRawArgs());
		$line = implode("\t", [date('Ymd'), date('His'), $cmd, "-> Error: $msg"]) . PHP_EOL;
		$fileContent = '';

		if (file_exists($file)) {
			$fileContent = file_get_contents($file);
		}
		file_put_contents($file, $fileContent . $line);
	}

	/**
	 * Log Error Message
	 * @param  string $msg
	 * @return false
	 */
	protected function error($msg) {
		$this->getPrinter()->error($msg);
		$this->logError($msg);
		return false;
	}
}
