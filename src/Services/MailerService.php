<?php


namespace App\Services;



use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MailerService
{
	private $mailer;
	private $translator;
	private $router;

	public function __construct(MailerInterface $mailer, TranslatorInterface $translator, UrlGeneratorInterface $router) {
		$this->mailer = $mailer;
		$this->translator = $translator;
		$this->router = $router;
	}

	public function sendMail(User $user, $url, $pathToTemplate) :void
	{
		$email = (new TemplatedEmail())
			->from(new Address('contact.snowtricks@gmail.com', 'SnowTricks'))
			->to(new Address($user->getEmail()))
			->subject($this->translator->trans('Mail_SignUp_Subject'))
			->htmlTemplate($pathToTemplate)
			->context([
				'user' => $user,
				'url' => $url
			])
		;

		$this->mailer->send($email);
	}
}