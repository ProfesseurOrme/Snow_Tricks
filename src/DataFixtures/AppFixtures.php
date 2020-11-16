<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Picture;
use App\Entity\Trick;
use App\Entity\User;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
		private $pictures = [
			'snowboard-backgrounds-unique-snowboarding-wallpaper-hd-c2b7e291a0-wallpapertag-for-you-of-snowboard-backgrounds.jpg',
			'Winter-sports-snowboard_1920x1080.jpg',
			'225771-5f56196dc2882.jpeg',
			'flip-flip-939878-5f56196dc2f32.jpeg',
			'front-flip-deux-5f56196dc3803.jpeg'
		];

		private $videos = [
			'https://www.youtube.com/watch?v=xhvqu2XBvI0',
			'https://www.youtube.com/watch?v=gMfmjr-kuOg',
			'https://www.youtube.com/watch?v=C-y70ZOSzE0',
			'https://www.youtube.com/watch?v=M_BOfGX0aGs',
			'https://www.dailymotion.com/embed/video/x2fs4j4',
			'https://www.dailymotion.com/embed/video/x2fu5fo'
		];

		private $category = [
			'Grab',
			'Rotation',
			'Goofy',
			'Loop',
			'Lose'
		];

		private $encoder;

		public function __construct(UserPasswordEncoderInterface $encoder)
		{
			$this->encoder = $encoder;
		}

	public function load(ObjectManager $manager)
    {
    		$faker = Faker\Factory::create(('fr_FR'));

				$user = new User();

				$plainPassword = 'snowtricksAdmin';

				$user->setUsername('admin')
					->setPassword($this->encoder->encodePassword($user,$plainPassword))
					->setEmail($faker->email)
					->setRoles(['ROLE_ADMIN'])
					->setIsVerified('true')
				;

    		$manager->persist($user);

    		$listCat = [];
    		foreach ($this->category as $category) {
    			$cat = new Category();
    			$cat->setName($category);
					$listCat[] = $cat;
    			$manager->persist($cat);
				}

        for($j=0;$j<20;$j++) {

        	$pictureKey = array_rand($this->pictures, 1);

					$trick = new Trick();
					$trick->setUser($user)
						->setCategory($faker->randomElement($listCat))
						->setName($faker->sentence(3, true))
						->setPicture($this->pictures[$pictureKey])
						->setDate($faker->dateTimeThisMonth('now',null))
						->setDescription($faker->paragraph(rand(6,30), true))
						;
					for($k=0;$k<rand(1,5);$k++)
					{
						$randomImageKey = array_rand($this->pictures, 1);

						$newPicture = new Picture();
						$newPicture->setFileName($this->pictures[$randomImageKey]);

						$trick->addPicture($newPicture);
					}

					for($l=0;$l<rand(1,5);$l++)
					{
						$randomVideoKey = array_rand($this->videos, 1);

						$newVideo = new Video();
						$newVideo->setEmbed($this->videos[$randomVideoKey]);

						$trick->addVideo($newVideo);
					}

					for($m=0;$m<rand(3,10);$m++) {
						$comment = new Comment();
						$comment->setUser($user)
							->setDate($faker->dateTimeThisMonth('now',null))
							->setContent($faker->paragraph(rand(6,30), true))
						;

						$trick->addComment($comment);
					}

					$manager->persist($trick);
					$manager->flush();
				}
    }
}
