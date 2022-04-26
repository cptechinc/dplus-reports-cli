<?php namespace App\Cmd\Help;
// Command Library
use Pauldro\Minicli\Cmd\Help\Controller;

/**
 * Help
 * Displays Main Help Screen showing the available commands
 */
class DefaultController extends Controller {
	const COMMAND_DEFINITIONS = [
		'spreadsheet'  => 'Convert JSON data into Spreadsheet',
		'log'          => 'View Log Data',
		'convert'      => 'Convert file into Spreadsheet',
	];

	/**
	 * Return Default Display
	 * @return void
	 */
	protected function display() {
		$printer = $this->getPrinter();
		$printer->info('Available Commands:');
		$this->displayCommands();
		$printer->newline();
		$printer->newline();
	}

	/**
	 * Display Commands and their Subcommands
	 * @return void
	 */
	protected function displayCommands() {
		$printer = $this->getPrinter();
		$cmdLength = $this->getLongestCommandLength() + 4;

		foreach ($this->getApp()->command_registry->getCommandMap() as $command => $sub) {
			$printer->newline();
			$printer->line(sprintf('%s%s', $printer->out($this->getCommandToLength($command, $cmdLength), 'info'), $this->getCommandDefinition($command)));

			if (is_array($sub)) {
				foreach ($sub as $subcommand) {
					if ($subcommand !== 'default') {
						$printer->line(sprintf('%s%s', $printer->spaces(2), $subcommand));
					}
				}
			}
		}
	}
}
