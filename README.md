## ADT\Mail\SingleRecipientMailer

Mailer which sends all the emails to one address. Usable for testing purpose. Original *To*, *Cc* and *Bcc* email addresses are stored in the subject of the email (for example: `To: origTo@example.com; origTo2@example.com; Cc: origCc@example.com | My email subject`).

## Installation

The best way to install is using [Composer](http://getcomposer.org/):

composer.json:
```
"repositories": [
	{
		"type": "git",
		"url": "https://github.com/AppsDevTeam/SingleRecipientMailer.git"
    }
]
```

```sh
$ composer require adt/single-recipient-mailer
```

## Usage

Add `singleRecipient` to your mailer parameters in your `config.local.neon`:
```neon
parameters:
	mailer:
		smtp: true
		host: my.smtp.host.com
		port: 465
		username: my@username.com
		password: myPassword
		secure: ssl
		singleRecipient: 'developers@myProject.com' # This is important.
```

Set the class in your `config.neon`:
```neon
services:
	nette.mailer:
		class: \ADT\Mail\SingleRecipientMailer(%mailer%)
```

You can also extend the class if you want to:
```php
namespace App\Model;
class Mailer extends \ADT\Mail\SingleRecipientMailer
{
...

	public function send(\Nette\Mail\Message $mail) {
		...

		parent::send($mail);    // Do not forget to call this!
	}
}
```
