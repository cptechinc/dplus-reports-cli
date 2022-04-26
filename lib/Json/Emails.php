<?php namespace Dplus\Json;

use Dplus\Json\Emails\Email;

/**
 * Emails
 * Container for Emails
 * 
 * @property array[Email] $to    Emails to Email to
 * @property Email        $from  Emails to Email from
 */
class Emails {
	protected $to    = [];
	protected $from  = null;

	/**
	 * Return if there are any Address to Email to
	 * @return bool
	 */
	public function hasTo() {
		return empty($this->to) === false;
	}

	/**
	 * Return Emails to Send to
	 * @return array[Email]
	 */
	public function getTo() {
		return $this->to;
	}

	/**
	 * Return From email
	 * @return Email
	 */
	public function getFrom() {
		return $this->from;
	}

	/**
	 * Set From Email from array
	 * @param array $data
	 */
	public function setFromEmailFromArray(array $data) {
		$email = new Email();
		$email->setFromArray($data);
		$this->from = $email;
	}

	/**
	 * Set To Emails, parsedfrom array
	 * @param  array $data
	 */
	public function setToEmailsFromArray(array $data) {
		$emails = [];

		foreach ($data as $key => $em) {
			$email = new Email();
			$email->setFromArray($em);
			$emails[$key] = $email;
		}
		$this->to = $emails;
	}
}