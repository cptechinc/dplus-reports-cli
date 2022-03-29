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
 * 
 * @property array $errors Errors Keyd by Email Address
 * @property array $files  Filepaths to add attachements
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
		$fromAddress = $emails->getFrom()->getAddress();

		foreach ($emails->getTo() as $key => $email) {
			$mail = new PHPMailer(true);

			try {
				$mail->setFrom($fromAddress);
				$mail->addAddress($email->getAddress());
				$mail->addReplyTo($fromAddress);
				$mail->Subject = $email->getSubject();
				$mail->Body    = "Your Report(s) are attached";
				
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
