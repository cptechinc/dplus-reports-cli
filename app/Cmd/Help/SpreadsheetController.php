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
	];
	const OPTIONS_DEFINITIONS = [
		// 'flag'   => 'Flag to attach',
		'report' => 'Report Code / COBOL Program ID',
		'id'     => 'Report ID',
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
