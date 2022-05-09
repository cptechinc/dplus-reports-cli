<?php namespace App\Cmd\Help;
// Mincli Library
use Minicli\App;
// Command Library
use Pauldro\Minicli\Cmd\Help\Controller;

/**
 * Help\LogController
 * Displays Help Screen for Log
 */
class ClearController extends Controller {
	const COMMAND = 'log clear';
	const DESCRIPTION = 'Empty Log(s)';
	const COMMAND_DEFINITIONS = [
		'log clear'     => 'Empty log(s)',
	];
	const OPTIONS = [
		'log' => 'log=LOG'
	];
	const OPTIONS_DEFINITIONS = [
		'log' => 'Log Name'
	];
	const SUBCOMMANDS = [];
}
