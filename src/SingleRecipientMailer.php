<?php

namespace ADT\Mail;

class SingleRecipientMailer extends \Nette\Object implements \Nette\Mail\IMailer {

	protected $recipient;

	/** @var \Nette\Mail\IMailer */
	protected $mailer;

	public function __construct(array $options = array()) {
		if (!empty($options['smtp'])) {
			$this->mailer = new \Nette\Mail\SmtpMailer($options);
		} else {
			$this->mailer = new \Nette\Mail\SendmailMailer();
		}

		if (isset($options['singleRecipient'])) {
			$this->recipient = $options['singleRecipient'];
		}
	}

	/**
	 * Returns TRUE if single recipient is set.
	 * @return bool
	 */
	public function isInDebugMode() {
		return $this->recipient !== NULL;
	}

	public function send(\Nette\Mail\Message $mail) {

		if ($this->isInDebugMode()) {
			$mail = clone $mail;

			$preSubject = ''
				. (!empty($mail->getHeader('To')) ? 'To: ' . join('; ', array_keys($mail->getHeader('To'))) . ' ' : '')
				. (!empty($mail->getHeader('Cc')) ? 'Cc: ' . join('; ', array_keys($mail->getHeader('Cc'))) . ' ' : '')
				. (!empty($mail->getHeader('Bcc')) ? 'Bcc: ' . join('; ', array_keys($mail->getHeader('Bcc'))) . ' ' : '')
				. '| ';
			$mail->clearHeader('To');
			$mail->clearHeader('Cc');
			$mail->clearHeader('Bcc');

			$mail->setSubject($preSubject . $mail->getSubject());

			$mail->addTo($this->recipient);
		}

		$this->mailer->send($mail);
	}
}
