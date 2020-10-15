<?php

namespace ADT\Mail;

use Nette\Mail;


class SingleRecipientMailer implements Mail\Mailer {

	/** @var string|NULL */
	protected $singleRecipient;

	/** @var Mail\Mailer */
	protected $next;

	public function __construct(Mail\Mailer $next, $singleRecipient = NULL) {
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

	public function send(Mail\Message $mail): void {

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

		$this->next->send($mail);
	}
}
