<?php namespace Dplus\Json;

/**
 * SaveFile
 * Container for Save File Data
 * 
 * @property string $dir             Directory to Save in
 * @property string $filename        Save Filename
 * @property bool   $appendDateTime  Append Date Time to Filename?
 * @property string $datetimeFormat  Datetime Format
 */
class SaveFile {
	protected $dir = '';
	protected $filename = '';
	protected $appendDatetime = false;
	protected $datetimeFormat = 'Ymd';

	/**
	 * Return Directory
	 * @return string
	 */
	public function getDir() {
		return $this->dir;
	}

	/**
	 * Return Filename 
	 * NOTE: does not include datetime appended
	 * @return string
	 */
	public function getFilename() {
		return $this->filename;
	}

	/**
	 * Return Filename with datetime appended if needed
	 * @return string
	 */
	public function filename() {
		if ($this->appendDatetime === false) {
			return $this->filename;
		}
		return $this->filename . "." . date($this->datetimeFormat);
	}

	/**
	 * Return if Filename is set
	 * @return bool
	 */
	public function hasFilename() {
		return strlen($this->filename) > 0;
	}

	/**
	 * Return if Directory is set
	 * @return bool
	 */
	public function hasDir() {
		return strlen($this->dir) > 0;
	}

	/**
	 * Set Directory
	 * @param string $dir
	 */
	public function setDir($dir = '') {
		$this->dir = $dir;
	}

	/**
	 * Set Filename
	 * @param string $name
	 */
	public function setFilename($name = '') {
		$this->filename = $name;
	}

	/**
	 * Set If datetime should be appended to filename
	 * @param bool|string $append (e.g. true|false || 'Y')
	 */
	public function setAppendDatetime($append) {
		if (is_bool($append)) {
			$this->appendDatetime = $append;
			return true;
		}
		$this->appendDatetime = strtoupper($append) == 'Y';
	}
}
