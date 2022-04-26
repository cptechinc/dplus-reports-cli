<?php namespace App\Cmd\Help;
// Mincli Library
use Minicli\App;
// Command Library
use Pauldro\Minicli\Cmd\Help\Controller;

/**
 * Help\ConvertController
 * Displays Help Screen for Convert
 */
class ConvertController extends Controller {
	const COMMAND = 'convert';
	const DESCRIPTION = 'Convert file into Spreadsheet';
	const COMMAND_DEFINITIONS = [
		'convert'     => 'Convert file into Spreadsheet',
	];
	const OPTIONS = [
		// 'flag'   => '--flag',
		'report' => 'report=REPORT',
		'id'     => 'id=ID',
		'co'     => 'co=CO',
	];
	const OPTIONS_DEFINITIONS = [
		// 'flag'   => 'Flag to attach',
		'report' => 'Report Code / COBOL Program ID',
		'id'     => 'Report ID',
		'co'     => 'Company Number',
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
