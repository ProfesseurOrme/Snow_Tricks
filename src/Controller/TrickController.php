<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Entity\Trick;
use App\Form\TrickFormType;
use App\Repository\TrickRepository;
use App\Services\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/tricks", name="trick")
 */
class TrickController extends AbstractController
{

  /**
   * @Route("/create_trick/", name="_create")
   * @param Request $request
   * @param UploadService $upload
   * @param EntityManagerInterface $manager
   * @return \Symfony\Component\HttpFoundation\Response
   */
    public function editTrick(Request $request, UploadService $upload, EntityManagerInterface $manager)
    {
        $trick = new Trick();
        $form = $this->createForm(TrickFormType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

          $newPicture = $upload->uploadFile($form['picture']->getData());


          foreach ($form['pictures']->getData() as $keyPicture => $pic) {
            $picture = new Picture();
            $newPictures = $upload->uploadFile($pic);
            $picture->setFileName($newPictures);

            $trick->addPicture($picture);
          }
          $trick->setPicture($newPicture);
          $trick->setDate(new \DateTime('NOW'));
          $manager->persist($trick);
          $manager->flush();

          $this->redirectToRoute('home');
        }
        return $this->render('tricks/edit_trick.html.twig', [
          'form' => $form->createView()
        ]);
    }

  /**
   * @Route("/{slug}", name="_detail")
   * @param Trick $trick
   */
    public function getDetailsTrick(Trick $trick) {

      $videos = [];

      foreach ($trick->getVideos() as $video) {

        if(preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $video->getEmbed(), $matches)) {

          $videos[] = '<iframe title="video figure" class="embed-responsive-item" src="https://www.youtube.com/embed/' . $matches[1] . '?rel=0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';

        } elseif (preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:dai\.ly\/|dailymotion\.com\/(?:(?:embed)\/))([^\?&\"'>]+)/", $video->getEmbed(), $matches)){

          $videos[] = '<iframe title="video figure" class="embed-responsive-item" src="https://www.dailymotion.com/embed/' .$matches[1].'" allowfullscreen></iframe>';
        }
      }

      return $this->render('tricks/detail-trick.html.twig', [
        'trick' => $trick,
        'videos' => $videos
      ]);
    }
}
