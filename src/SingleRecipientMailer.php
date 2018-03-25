<?php

namespace ADT\Mail;

use Nette\Mail;


class SingleRecipientMailer extends \Nette\Object implements Mail\IMailer {

	/** @var string|NULL */
	protected $singleRecipient;

	/** @var Mail\IMailer */
	protected $next;

	public function __construct(Mail\IMailer $next, $singleRecipient = NULL) {
		$this->next = $next;
		$this->singleRecipient = $singleRecipient;
	}

	/**
	 * Returns TRUE if single recipient is set.
	 * @return bool
	 */
	public function hasSingleRecipient() {
		return !!$this->singleRecipient;
	}

	public function send(Mail\Message $mail) {

		if ($this->hasSingleRecipient()) {
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

			$mail->addTo($this->singleRecipient);
		}

		return $this->next->send($mail);
	}
}
