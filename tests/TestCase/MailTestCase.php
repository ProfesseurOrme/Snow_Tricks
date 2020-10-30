<?php


	namespace App\Tests\TestCase;


	use App\Entity\User;
	use App\Services\MailerService;
	use PHPUnit\Framework\TestCase;
	use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

	class MailTestCase extends TestCase
	{

		public function test() {
			$user = new User();
			$user->setUsername('MMartin')
				->setPassword('testpassword')
				->setIsVerified('true')
				->setEmail('mail@test.fr')
				->setRoles(["ROLE_USER"])
				->setResetToken('')
			;

			$url = "/path/to/url";
			$template= 'home.html.twig';

			$mail = new MailerService();

			$this->assertNull($mail->sendMail($user,$url,$template));
		}
	}