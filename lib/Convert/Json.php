<?php namespace Lib\Convert;
// Dplus Reports
use Lib\Reports\Json as Base;

/**
 * Json
 * Container for the JSON Request
 * 
 * @property string   $srcFilepath    Filepath to Source File
 * @property array	  $fields	 Column Data
 */
class Json extends Base {
	protected $srcFile = '';
	protected $fields  = [];

	/** @var array Justify codes for each fieldtype code */
	const FIELDTYPE_JUSTIFY = [
		'C' => 'left',
		'D' => 'left',
		'I' => 'right',
		'N' => 'right'
	];

	/**
	 * Return Src Filepath
	 * @return string
	 */
	public function getSrcFilepath() {
		return $this->srcFile;
	}

	/**
	 * Return Fields Data
	 * @return array
	 */
	public function getFields() {
		return $this->fields;
	}

	/**
	 * Return Field Justify Code for field
	 * @param  string $key Fieldname / Key
	 * @return string
	 */
	public function getFieldJustify($key) {
		$field = $this->fields[$key];
		return self::FIELDTYPE_JUSTIFY[$field['type']];
	}

	/**
	 * Parse JSON data into properties
	 * @return void
	 */
	protected function parseJson() {
		$this->id = $this->json['reportid'];
		
		if (array_key_exists('columnlabels', $this->json)) {
			$this->fields = $this->json['columnlabels'];
		}
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