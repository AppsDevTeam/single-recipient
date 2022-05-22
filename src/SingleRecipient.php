<?php

namespace ADT\SingleRecipient;

use Nette\Mail\Message;

trait SingleRecipient
{
	public function initSingleRecipient(Message $mail, ?string $singleRecipient)
	{
		$preSubject = ''
			. (!empty($mail->getHeader('To')) ? 'To: ' . join('; ', array_keys($mail->getHeader('To'))) . ' ' : '')
			. (!empty($mail->getHeader('Cc')) ? 'Cc: ' . join('; ', array_keys($mail->getHeader('Cc'))) . ' ' : '')
			. (!empty($mail->getHeader('Bcc')) ? 'Bcc: ' . join('; ', array_keys($mail->getHeader('Bcc'))) . ' ' : '')
			. '| ';
		$mail->clearHeader('To');
		$mail->clearHeader('Cc');
		$mail->clearHeader('Bcc');

		$mail->setSubject($preSubject . $mail->getSubject());

		$mail->addTo($singleRecipient);
	}
}
