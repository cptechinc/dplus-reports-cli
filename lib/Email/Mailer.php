<?php namespace Lib\Email;
// PHPMailer Library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Dplus Json Emails;
use Dplus\Reports\Json\Report\Emails;

/**
 * Mailer
 * Emails Report Spreadsheet
 */
class Mailer {
	public $errors = [];

	protected $files = [];

	/**
	 * Add Attachment
	 * @param  string $filepath Full Filepath
	 * @return string
	 */
	public function addFile($filepath = '') {
		$files = $this->files;

		if (empty($filepath)) {
			return false;
		}
		if (file_exists($filepath) === false) {
			return false;
		}
		$files[] = $filepath;
		$this->files = $files;
	} 

	/**
	 * Send Email(s)
	 * @param  Emails $emails
	 * @return array
	 */
	public function mail(Emails $emails) {
		$errors = [];
		$mail = new PHPMailer(true);
		$fromAddress = $emails->getFrom()->getAddress();

		foreach ($emails->getTo() as $key => $email) {
			try {
				$mail->setFrom($fromAddress);
				$mail->addAddress($email->getAddress());
				$mail->addReplyTo($fromAddress);
				$mail->Subject = $email->getSubject();
				$mail->Body    = $email->getSubject();
				
				foreach ($this->files as $file) {
					$mail->addAttachment($file);
				}
				$mail->send();
			} catch (Exception $e) {
				$msg = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
				$errors[$email->getAddress()] = $msg;
			}
		}
		return $errors;
	}
}
