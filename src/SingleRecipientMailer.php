<?php

namespace ADT\Mail;

class SingleRecipientMailer extends \Nette\Mail\SmtpMailer
{

	protected $recipient;

	public function __construct(array $options = array()) {
		parent::__construct($options);

		if (isset($options['singleRecipient'])) {
			$this->recipient = $options['singleRecipient'];
		}
	}

	public function send(\Nette\Mail\Message $mail) {

		if ($this->recipient !== NULL) {
			$mail = clone $mail;

			$preSubject = ''
							. (! empty($mail->getHeader('To')) ? 'To: '. join('; ', array_keys($mail->getHeader('To'))) . ' ' : '')
							. (! empty($mail->getHeader('Cc')) ? 'Cc: '. join('; ', array_keys($mail->getHeader('Cc'))) . ' ' : '')
							. (! empty($mail->getHeader('Bcc')) ? 'Bcc: '. join('; ', array_keys($mail->getHeader('Bcc'))) . ' ' : '')
							. '| ';
			$mail->clearHeader('To');
			$mail->clearHeader('Cc');
			$mail->clearHeader('Bcc');

			$mail->setSubject($preSubject. $mail->getSubject());

			$mail->addTo($this->recipient);
		}

		parent::send($mail);
	}
}
