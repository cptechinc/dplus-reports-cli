<?php namespace Lib\Json\Emails;

/**
 * Email
 * Data Container Class for Emailing
 * 
 * @property string $address  Email Address
 * @property string $name     Name
 * @property string $subject  Subject
 */
class Email {
	protected $address;
	protected $name;
	protected $subject;

	/**
	 * Return Email Address
	 * @return string
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * Set Email Address
	 * @param  string $address
	 */
	public function setAddress($address) {
		$this->address = $address;
	}

	/**
	 * Set Name
	 * @param  string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Return Name
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Set Subject
	 * @param  string $name
	 */
	public function setSubject($subject) {
		$this->subject = $subject;
	}

	/**
	 * Return Name
	 * @return string
	 */
	public function getSubject() {
		return $this->subject;
	}

	/**
	 * Set Values from arrry
	 * @param array $data
	 */
	public function setFromArray(array $data) {
		foreach ($data as $key => $value) {
			if (property_exists($this, $key)) {
				$this->$key = $value;
			}
		}
	}
}

