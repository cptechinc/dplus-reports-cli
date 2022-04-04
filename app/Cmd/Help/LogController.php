<?php namespace App\Cmd\Help;
// Mincli Library
use Minicli\App;
// Command Library
use Pauldro\Minicli\Cmd\Help\Controller;

/**
 * Help\LogController
 * Displays Help Screen for Log
 */
class LogController extends Controller {
	const COMMAND = 'log';
	const DESCRIPTION = 'View Log Data';
	const COMMAND_DEFINITIONS = [
		'log'     => 'View Last Logged Command',
	];
	const OPTIONS = [];
	const OPTIONS_DEFINITIONS = [];
	const SUBCOMMANDS = [];
}
