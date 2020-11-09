<?php

namespace App\Tests\UnitTests;

use App\Entity\Category;
use App\Entity\Picture;
use App\Entity\Trick;
use App\Entity\User;
use App\Entity\Video;
use PHPUnit\Framework\TestCase;

class AppUnitTest extends TestCase
{

	public function testAddTrick()
    {
			$trick = new Trick();

			$date = new \DateTime('2020-11-09 09:20:27');

			$trick->setName("Back Flip Goofy")
				->setDate($date)
				->setDescription("C'est une superbe figure")
			;

			$category = new Category();
			$category->setName("Grab");

			$user = new User();
			$user->setUsername("Admin")
				->setIsVerified(true)
				->setRoles(["ROLE_ADMIN"])
				->setEmail("admin_mail@gmail.com")
				->setPassword("adminpassword")
			;

			$trick->setCategory($category)
				->setUser($user)
			;

			$picture = new Picture();
			$video = new Video();

			$picture->setFileName("flip-flip-939878-5f56196dc2f32.jpeg");
			$video->setEmbed("https://www.youtube.com/watch?v=xhvqu2XBvI0");

			$trick->addPicture($picture)
				->addVideo($video)
			;

			$this->assertEquals("Back Flip Goofy", $trick->getName());
			$this->assertEquals($date, $trick->getDate());
			$this->assertEquals("C'est une superbe figure", $trick->getDescription());
			$this->assertEquals($category->getName(), $trick->getCategory()->getName());

			foreach ($trick->getPictures() as $pictures) {
				$this->assertEquals("flip-flip-939878-5f56196dc2f32.jpeg", $picture->getFileName());
			}

			foreach ($trick->getVideos() as $videos) {
				$this->assertEquals("https://www.youtube.com/watch?v=xhvqu2XBvI0", $videos->getEmbed());
			}

			$this->assertEquals("Grab", $trick->getCategory()->getName());
			$this->assertEquals("Admin", $trick->getUser()->getUsername());
		}
}
