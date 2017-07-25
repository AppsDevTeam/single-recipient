## ADT\Mail\SingleRecipientMailer

Mailer which sends all the emails to one address. Usable for testing purposes.
Original *To*, *Cc* and *Bcc* email addresses are stored in the subject of the email
(for example: `To: origTo@example.com; origTo2@example.com; Cc: origCc@example.com | My email subject`).

## Installation

The best way to install is using [Composer](http://getcomposer.org/):

```sh
$ composer require adt/single-recipient-mailer
```

## Usage

### via config

Register `\ADT\Mail\SingleRecipientMailer` in your `config.neon` to use `@smtpMailer` and
redirect all emails to `developers@myProject.com`:
```neon
services:
	smtpMailer:
		class: \Nette\Mail\SmtpMailer(%mailer%)
		autowired: no # this is important
	
	nette.mailer:
		class: \ADT\Mail\SingleRecipientMailer(@smtpMailer, 'developers@myProject.com')
		
```

The `autowired: no` option is important because Nette DI container would not know
which `\Nette\Mail\IMailer` to inject in your application. By setting `autowired: no` on
SMTP mailer only one instance of `IMailer` interface remains.

You cannot set `autowired: no` on `nette.mailer` because your application
would not be able to inject it.

It is also important that you autowire `\Nette\Mail\IMailer` throughout your application.

### via inheritance

You can also extend the class if you want to:
```php
namespace App\Model;

class Mailer extends \ADT\Mail\SingleRecipientMailer {

	public function __construct() {
		parent::__construct(new SmtpMailer(...), 'developers@myProject.com');
	}

...

	public function send(\Nette\Mail\Message $mail) {
		...

		parent::send($mail);    // Do not forget to call this!
	}
}
```

You can disable redirecting to single recipient by passing
empty value (e.g. `NULL` or zero-length string).