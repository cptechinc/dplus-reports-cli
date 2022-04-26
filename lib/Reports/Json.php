<?php namespace Lib\Reports;

use Lib\Json\Json as BaseJson;

/**
 * Json
 * Container for the JSON Data for a Report
 * 
 * @property string   $report 	 Report Code
 * @property string   $id		 Report ID
 * @property array	  $fields	 Column Data
 * @property array	  $data		 The Report Data
 * @property bool	  $hasHeaders  Does Report Have Headings? (Different from Column Headings)
 */
class Json extends BaseJson {
	protected $report = '';
	protected $id	  = '';
	protected $fields = [];
	protected $data   = [];
	protected $hasHeaders = false;

	/** @var array Justify codes for each fieldtype code */
	const FIELDTYPE_JUSTIFY = [
		'C' => 'left',
		'D' => 'left',
		'I' => 'right',
		'N' => 'right'
	];

	public function __construct($report, $id) {
		parent::__construct();
		$this->report = $report;
		$this->id = $id;
	}

	/**
	 * Return Fields Data
	 * @return array
	 */
	public function getFields() {
		return $this->fields;
	}

	/**
	 * Return Report Data
	 * @return array
	 */
	public function getData() {
		return $this->data;
	}

	/**
	 * Return if Report Has Headers
	 * @return bool
	 */
	public function hasHeaders() {
		return $this->hasHeaders;
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
		parent::parseJson();
		$this->id = $this->json['reportid'];

		if (array_key_exists('columnlabels', $this->json)) {
			$this->fields = $this->json['columnlabels'];
		}

		if (array_key_exists('data', $this->json)) {
			$this->data = $this->json['data'];
		}
		$this->hasHeaders = $this->parseJsonForHeaders();
	}

	/**
	 * Determines if JSON has heading indexes for Report
	 * @return bool
	 */
	protected function parseJsonForHeaders() {
		foreach ($this->data as $record) {
			if (array_key_exists('header', $record) && $record['header']) {
				return true;
			}
		}
		return false;
	}
}