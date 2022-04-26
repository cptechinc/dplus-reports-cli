<?php namespace Lib\PhpSpreadsheet\Reader;
// PHP Spreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv as Reader;
use PhpOffice\PhpSpreadsheet\Cell\Cell;

use Lib\PhpSpreadsheet\StringValueBinder;

/**
 * Reader\Tsv
 * 
 * Handles Getting the Tab-separated Reader, Returing Spreadsheets
 */
class Tsv {
	private static $reader;

	/**
	 * Return Reader
	 * @return Reader
	 */
	public static function reader() {
		if (empty(self::$reader)) {
			$reader = new Reader();
			$reader->setInputEncoding('CP1252');
			$reader->setSheetIndex(0);
			$reader->setDelimiter("\t");
			self::$reader = $reader;
		}
		return self::$reader;
	}

	/**
	 * Use Reader to return Spreadsheet
	 * @param  string $filepath  /path/to/file
	 * @return Spreadsheet
	 */
	public static function getSpreadsheet($filepath) {
		Cell::setValueBinder(new StringValueBinder());

		$reader = self::reader();
		$reader->setReadDataOnly(true);
		return $reader->load($filepath);
	}
}