<?php namespace App\Cmd\Help;
// Mincli Library
use Minicli\App;
// Command Library
use Pauldro\Minicli\Cmd\Help\Controller;

/**
 * Help\SpreadsheetController
 * Displays Help Screen for Spreadsheet
 */
class SpreadsheetController extends Controller {
	const COMMAND = 'spreadsheet';
	const DESCRIPTION = 'Convert JSON data into Spreadsheet';
	const COMMAND_DEFINITIONS = [
		'spreadsheet'     => 'Convert JSON data into Spreadsheet',
	];
	const OPTIONS = [
		// 'flag'   => '--flag',
		'report' => 'report=REPORT',
		'id'     => 'id=ID',
		'co'     => 'co=CO',
		'debug'  => '--debug'
	];
	const OPTIONS_DEFINITIONS = [
		// 'flag'   => 'Flag to attach',
		'report' => 'Report Code / COBOL Program ID',
		'id'     => 'Report ID',
		'co'     => 'Company Number',
		'debug'  => 'Run in debug? (displays debug info, file(s) location(s), execution duration)'
	];
	const SUBCOMMANDS = [];

	public function handle() {
		if (in_array($this->input->lastArg(), static::SUBCOMMANDS)) {
			$this->displaySubcommand();
			return true;
		}
		$this->display();
	}
}
