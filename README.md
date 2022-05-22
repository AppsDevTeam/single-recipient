## ADT\Mail\SingleRecipientMailer

Component modifies `Nette\Mail\Message` to send all the emails to one address. Suitable for non production environments.

Original *To*, *Cc* and *Bcc* email addresses are stored in the subject of the email (for example: `To: origTo@example.com; origTo2@example.com; Cc: origCc@example.com | My email subject`).

## Installation

```sh
$ composer require adt/single-recipient-mailer
```

## Usage

### via trait

Use `ADT\SingleRecipient\SingleRecipient` trait in your mailer and then use `applySingleRecipient` method to apply a single recipient logic to an original message.

```php
namespace App\Model;

use ADT\SingleRecipient\SingleRecipient;
use \Nette\Mail\SendmailMailer;

class Mailer extends SendmailMailer
{
	use SingleRecipient;

	public function send(Message $mail): void
	{
		if ($this->singleRecipient) {
			$this->applySingleRecipient($mail, 'developers@myproject.com');
		}

		$this->send($mail);
	}
}
```

### via config

Register `ADT\SingleRecipient\SingleRecipientMailer` in your `config.neon` to use `@sendmailMailer` and
redirect all emails to `developers@myProject.com`:

```neon
services:
	sendmailMailer:
		class: Nette\Mail\SendmailMailer
		autowired: no # this is important

	mail.mailer: \ADT\SingleRecipient\SingleRecipientMailer(@sendmailMailer, 'developers@myproject.com')
```

The `autowired: no` option is important because Nette DI container would not know
which `\Nette\Mail\IMailer` to inject in your application.

### via inheritance

You can also extend the class if you want to:

```php
namespace App\Model;

use Nette\Mail\SendmailMailer;

class Mailer extends \ADT\SingleRecipient\SingleRecipientMailer 
{
	public function __construct() 
	{
		parent::__construct(new SendMailMailer, 'developers@myproject.com');
	}

	public function send(\Nette\Mail\Message $mail) 
	{
		parent::send($mail); # do not forget to call this
	}
}
```

You can disable redirecting to single recipient by passing
empty value (e.g. `NULL` or zero-length string).