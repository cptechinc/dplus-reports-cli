<?php namespace Lib\Files;

/**
 * File
 * Contains File Info
 * 
 * @property string $filepath  Full File Path
 * @property string $directory Directory Path
 * @property string $filename  Filename
 * @property string $extension File Extension
 */
class File {
	protected $filepath  = '';
	protected $directory = '';
	protected $filename  = '';
	protected $extension = '';
	
	/**
	 * Set File Directory
	 * @param string $dir File Directory
	 */
	public function setDirectory($dir) {
		$this->directory = $dir;
	}

	/**
	 * Set Filename
	 * @param string $name Filename
	 */
	public function setFilename($name) {
		$this->filename = $name;
	}

	/**
	 * Set File Extension
	 * @param string $extension File Extension
	 */
	public function setExtension($extension) {
		$this->extension = $extension;
	}

	/**
	 * Return File Path
	 * @return string
	 */
	public function getFilepath() {
		return $this->filepath;
	}

	/**
	 * Return File Directory
	 * @return string
	 */
	public function getDirectory() {
		return $this->directory;
	}

	/**
	 * Return Filename
	 * @return string
	 */
	public function getfilename() {
		return $this->filename;
	}

	/**
	 * Return File Extension
	 * @return string
	 */
	public function getExtension() {
		return $this->extension;
	}
	
	/**
	 * Set Filepath
	 * @param  string $filepath
	 * @return bool
	 */
	public function setFilepath($filepath) {
		if (file_exists($filepath) === false) {
			return false;
		}
		$this->filepath = $filepath;
		$this->parseFilepath();
		return true;
	}

	/**
	 * Parse Filepath data
	 * @return void
	 */
	protected function parseFilepath() {
		$file = pathinfo($this->filepath);
		$this->directory = $file['dirname'];
		$this->extension = $file['extension'];
		$this->filename  = $file['filename'];
	}

	/**
	 * Return File Path Generated from parts
	 */
	public function setFilepathFromParts() {
		$this->filepath = rtrim($this->directory, '/') . "/" . $this->filename . "." . $this->extension;
	}
}