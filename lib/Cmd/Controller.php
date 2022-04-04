<?php namespace Lib\Cmd;
// Dotenv Env Library
use Dotenv\Dotenv;
use Dotenv\RuntimeException;
// Mincli Library
use Minicli\App;
// Command Library
use Pauldro\Minicli\Cmd\Controller as Base;
// Dplus Reports
use Dplus\Reports\Json\Fetcher as JsonFetcher;
use Dplus\Reports\Json\Report;
use Dplus\Reports\Json\Spreadsheets\Writer;


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
	const LOG_CMD_NAME = 'commands.log';

	/**
	 * Return the Filepath to the command log
	 * @return string
	 */
	protected function getLogCmdFilePath() {
		return $this->app->config->log_dir . '/' . static::LOG_CMD_NAME;
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
}
