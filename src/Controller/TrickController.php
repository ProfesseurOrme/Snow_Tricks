<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Picture;
use App\Entity\Trick;
use App\Form\CommentFormType;
use App\Form\TrickFormType;
use App\Services\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/tricks", name="trick")
 */
class TrickController extends AbstractController
{

  private $manager;
  private $upload;

    public function __construct(EntityManagerInterface $manager, UploadService $upload) {
      $this->manager = $manager;
      $this->upload = $upload;
    }

  /**
   * @Route("/create_trick/", name="_create")
   * @Route("/edit-{slug}", options={"expose"=true},name="_edit")
   * @param Request $request
   * @param Trick $trick
   * @return Response
   */
    public function editTrick(Request $request, Trick $trick = null)
    {
        if(!$trick) {
          $trick = new Trick();
        }

        $form = $this->createForm(TrickFormType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

          $newPicture = $this->upload->uploadFile($form['picture']->getData());

          foreach ($form['pictures']->getData() as $keyPicture => $pic) {
            $picture = new Picture();
            $newPictures = $this->upload->uploadFile($pic);
            $picture->setFileName($newPictures);

            $trick->addPicture($picture);
          }
          $trick->setPicture($newPicture);
          $trick->setDate(new \DateTime('NOW'));
          $this->manager->persist($trick);
          $this->manager->flush();

          $this->redirectToRoute('home');
        }
        return $this->render('tricks/edit_trick.html.twig', [
          'form' => $form->createView(),
          'trick' => ($trick) ? $trick : null
        ]);
    }

  /**
   * @Route("/{slug}", options={"expose"=true},name="_detail")
   * @param Trick $trick
   * @param Request $request
   * @return Response
   */
    public function getDetailsTrick(Trick $trick, Request $request) {

      $comment = new Comment();

      $formComment = $this->createForm(CommentFormType::class, $comment);
      $formComment->handleRequest($request);

      $videos = [];

      foreach ($trick->getVideos() as $video) {

        if(preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $video->getEmbed(), $matches)) {

          $videos[] = '<iframe title="video figure" class="embed-responsive-item" src="https://www.youtube.com/embed/' . $matches[1] . '?rel=0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';

        } elseif (preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:dai\.ly\/|dailymotion\.com\/(?:(?:embed)\/))([^\?&\"'>]+)/", $video->getEmbed(), $matches)){

          $videos[] = '<iframe title="video figure" class="embed-responsive-item" src="https://www.dailymotion.com/embed/' .$matches[1].'" allowfullscreen></iframe>';
        }
      }

      if($formComment->isSubmitted() && $formComment->isValid()) {
        echo 'pouet';
      }

      return $this->render('tricks/detail-trick.html.twig', [
        'trick' => $trick,
        'videos' => $videos,
        'comments' => ($trick->getComments()->isEmpty()) ? null : $trick->getComments(),
        'formComment' => $formComment->createView()
      ]);
    }

  /**
   * @Route("/delete-trick-{slug}", options={"expose"=true}, name="_delete")
   * @param Trick $trick
   */
    public function deleteTrick(Trick $trick) {

      $this->manager->remove($trick);
      $this->manager->flush();

      $this->redirectToRoute('home');
    }
}
