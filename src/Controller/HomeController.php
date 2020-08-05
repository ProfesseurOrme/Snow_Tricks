<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("", name="home")
     */
    public function index(TrickRepository $trickRepository)
    {

      $tricks = $trickRepository->findAll();

      return $this->render('home/index.html.twig', [
          'controller_name' => 'HomeController',
          'tricks' => $tricks
      ]);
    }
}
