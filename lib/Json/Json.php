<?php namespace Lib\Json;

/**
 * Json
 * Container for the JSON Data
 * 
 * @property array	  $json		 Full JSON data
 * @property Emails   $emails	   Emails to Send To / From
 * @property SaveFile $saveFile    Directory, filename to save to
 */
class Json {
	protected $json      = [];
	protected $emails    = null;
	protected $saveFile  = null;

	public function __construct() {
		$this->emails	= new Emails();
		$this->saveFile = new SaveFile();
	}

	/**
	 * Return Emails
	 * @return Emails
	 */
	public function getEmails() {
		return $this->emails;
	}

	/**
	 * Return savefile
	 * @return SaveFile
	 */
	public function getSaveFile() {
		return $this->saveFile;
	}

	/**
	 * Set JSON
	 * @param array $json
	 * @return void
	 */
	public function setJson($json = []) {
		$this->json = $json;
		$this->parseJson();
	}

	/**
	 * Parse JSON data into properties
	 * @return void
	 */
	protected function parseJson() {
		$this->parseJsonEmails();
		$this->parseJsonSaveFile();
	}

	/**
	 * Set / Parse Emails from JSON
	 */
	protected function parseJsonEmails() {
		if (array_key_exists('email', $this->json) && empty($this->json['email']) === false) {
			$this->emails->setToEmailsFromArray($this->json['email']);
			$this->emails->setFromEmailFromArray(['address' => $this->json['fromemailaddress'], 'name' => $this->json['fromemailname']]);
		}
	}

	/**
	 * Set / Parse Save File data from JSON
	 * @return bool
	 */
	protected function parseJsonSaveFile() {
		if (empty($this->json['directory']) || empty($this->json['filename'])) {
			return false;
		}
		$this->saveFile->setDir($this->json['directory']);
		$this->saveFile->setfilename($this->json['filename']);
		$this->saveFile->setAppendDatetime($this->json['appenddatetime']);
		return true;
	}
}