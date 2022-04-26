<?php namespace Lib\Convert;
// Dplus Reports
use Lib\Reports\Json as Base;

/**
 * Json
 * Container for the JSON Request
 * 
 * @property string   $srcFilepath    Filepath to Source File
 */
class Json extends Base {
	protected $srcFile     = '';

	/**
	 * Return Src Filepath
	 * @return string
	 */
	public function getSrcFilepath() {
		return $this->srcFile;
	}

	/**
	 * Parse JSON data into properties
	 * @return void
	 */
	protected function parseJson() {
		$this->id = $this->json['reportid'];
		$this->parseJsonEmails();
		$this->parseJsonSaveFile();
		$this->parseJsonForSrcfile();
	}

	/**
	 * Parse and set Src file
	 * @return bool
	 */
	protected function parseJsonForSrcfile() {
		if (array_key_exists('textfile', $this->json) === false) {
			return false;
		}
		$filepath = $this->json['textfile'];

		if (file_exists($filepath) === false) {
			return false;
		}
		$this->srcFile = $filepath;
		return true;
	}
}