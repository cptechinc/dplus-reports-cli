<?php namespace Lib\Files;

/**
 * Copier
 * Copies Files
 * 
 * @property string $originalFilePath      Original Full File Path /dir/to/file.ext
 * @property File   $originalFile
 * @property string $destinationDirectory  Directory to Copy New file to /dest/dir/
 * @property string $destinationFilename   Filename
 * @property string $lastCopyFile          Last File that was made
 * @property string $error                 Last Error Message
 */
class Copier {
	protected $originalFilepath    = '';
	protected $originalFile;
	protected $destinationDirectory = '';
	protected $destinationFilename  = '';
	public    $lastCopyFile         = '';
	public    $error                = '';

	/**
	 * Copy File
	 * @return bool
	 */
	public function copy() {
		$this->error = '';

		if (empty($this->originalFilepath)) {
			$this->error = "Original File not set";
			return false;
		}

		if (file_exists($this->originalFilepath) === false) {
			$this->error = "Original File '$this->originalFilepath' not found";
			return false;
		}

		if (empty($this->destinationDirectory)) {
			$this->error = "Destination Directory not set";
			return false;
		}

		if (is_dir($this->destinationDirectory) === false) {
			$this->error = "Destination Directory '$this->destinationDirectory' not found";
			return false;
		}

		$copyFile = new File();
		$copyFile->setDirectory($this->destinationDirectory);
		$copyFile->setFilename($this->destinationFilename ? $this->destinationFilename : $this->originalFile->getFilename());
		$copyFile->setExtension($this->originalFile->getExtension());
		$copyFile->setFilepathFromParts();

		$success = copy($this->originalFilepath, $copyFile->getFilepath());
		if ($success === false) {
			return false;
		}
		$this->lastCopyFile = $copyFile->getFilepath();
		return true;
	}
	
	/**
	 * Set Original Filepath, parse File
	 * @param  string $filepath
	 * @return bool
	 */
	public function setOriginalFilepath($filepath) {
		$this->originalFilepath = $filepath;

		$file = new File();

		if ($file->setFilepath($filepath) === false) {
			return false;
		}
		$this->originalFile = $file;
		return true;
	}

	/**
	 * Set Destination Directory
	 * @param  string $dir  Destination Directory
	 * @return void
	 */
	public function setDestinationDirectory($dir) {
		$this->destinationDirectory = $dir;
	}

	/**
	 * Set Destination Filename
	 * @param  string $name  Filename
	 * @return void
	 */
	public function setDestinationFilename($name) {
		$this->destinationFilename = $name;
	}
}