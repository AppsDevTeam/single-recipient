<?php

namespace ADT\SingleRecipient;

use Nette\Mail\Mailer;
use Nette\Mail\Message;

class SingleRecipientMailer implements Mailer
{
	use SingleRecipient;

	protected Mailer $next;

	protected ?string $singleRecipient;

	public function __construct(Mailer $next, ?string $singleRecipient)
	{
		$this->next = $next;
		$this->singleRecipient = $singleRecipient;
	}

	public function send(Message $mail): void
	{
		if ($this->singleRecipient) {
			$this->applySingleRecipient($mail, $this->singleRecipient);
		}

		$this->next->send($mail);
	}
}
