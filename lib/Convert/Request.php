<?php namespace Lib\Convert;
// Dplus Reports
use Lib\Json\Fetcher;

/**
 * Request
 * 
 * @property string      $code      Report Code (pol, program name)
 * @property string      $id        Report ID
 * @property string      $errorMsg  Error Message
 * @property Json        $json      Container for JSON data
 */
class Request {
	protected $code = '';
	protected $id   = '';
	protected $json = null;
	public    $errorMsg = '';

	/**
	 * Set Report ID
	 * @param  string $id
	 * @return void
	 */
	public function setCode($code) {
		$this->code = $code;
		return $this;
	}

	/**
	 * Set Report ID
	 * @param  string $id
	 * @return void
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * Return Report Code
	 * @return string
	 */
	public function getCode() {
		return $this->code;
	}

	/**
	 * Return Report ID
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Return Json
	 * @return Json
	 */
	public function getJson() {
		return $this->json;
	}

	/**
	 * Returns Code ID 
	 */
	protected function generateCodeId() {
		return $this->code . '-' . $this->id;
	}

	/**
	 * Check if Report JSON exists
	 * @return bool
	 */
	public function exists() {
		return Fetcher::instance()->exists($this->generateCodeId());
	}

	/**
	 * Delete Report JSON
	 * @return string
	 */
	public function delete() {
		return Fetcher::instance()->delete($this->generateCodeId());
	}

	/**
	 * Return Report JSON
	 * @return bool
	 */
	public function fetch() {
		if ($this->exists() === false) {
			$this->errorMsg = 'Report ' . $this->generateCodeId() . ' not found';
			return false;
		}
		$json = Fetcher::instance()->fetch(($this->generateCodeId()));

		if (empty($json)) {
			$this->errorMsg = Fetcher::instance()->errorMsg;
			return false;
		}
		$jsonContainer = new Json($this->code, $this->id);
		$jsonContainer->setJson($json);
		$this->json = $jsonContainer;
		return true;
	}
}