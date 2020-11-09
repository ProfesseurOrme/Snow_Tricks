<?php

namespace App\Tests\UnitTests\FunctionalTests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppFunctionalTest extends WebTestCase
{
	public function testRedirectionToRegistration()
	{
		$client = static::createClient();
		$crawler = $client->request('GET', '/');

		$link = $crawler->selectLink('S\'enregistrer')->link();

		$client->click($link);

		$this->assertSelectorTextContains('h3', 'Création du compte');
	}

	public function testClientRegistration()
	{
		$client = static::createClient();
		$crawler = $client->request('GET', '/registration');

		$form = $crawler->filter('form[name=registration_form]')->form();

		$form['registration_form[username]'] = 'TestAccount';
		$form['registration_form[email]'] = 'testaccount@mail.com';
		$form['registration_form[plainPassword]'] = 'testpassword123';

		$client->submit($form);

		$crawler = $client->followRedirect();

		$this->assertEquals(1, $crawler->filter('div.alert.alert-success.alert-popup')->count());
	}
}
