<?php namespace Lib\PhpSpreadsheet;
// PhpSpreadsheet Library
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

/**
 * Writer
 * Base Class for Writing Spreadsheets
 *
 * @property string $directory  Directory to Write file to
 * @property string $filename   File Name
 * @property string $fileprefix Prefix to Filename
 */
abstract class Writer {
	const EXTENSION = 'txt';
	public $filename = '';
	public $fileprefix = '';
	public $lastWrittenFile = '';
	protected static $dir;

	/**
	 * Set Directory to Write Files into
	 * @param  string $dir
	 * @return void
	 */
	public static function setDir($dir) {
		self::$dir = $dir;
	}

	public function __construct() {
		$this->filename   = 'spreadsheet';
		$this->fileprefix = session_id();
	}

	/**
	 * Return Spreadsheet File Writer
	 * @return BaseWriter
	 */
	abstract protected function getWriter(Spreadsheet $spreadsheet);

	/**
	 * Return Filename
	 * @return string
	 */
	public function getWritefilename() {
		return $this->fileprefix.'-'.$this->filename;
	}

	/**
	 * Return Filepath for file
	 * @return string
	 */
	public function getFilepath() {
		return self::$dir.$this->getWritefilename().'.'.static::EXTENSION;
	}

	/**
	 * Writes Spreadsheet to File
	 * @param  Spreadsheet $spreadsheet Spreadsheet
	 * @return bool
	 */
	public function write(Spreadsheet $spreadsheet) {
		$writer  = $this->getWriter($spreadsheet);
		$writer->save($this->getFilepath());
		$saved = file_exists($this->getFilepath());

		if ($saved === false) {
			return false;
		}
		$this->lastWrittenFile = $this->getFilepath();
		return true;
	}
}
