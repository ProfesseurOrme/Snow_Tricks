<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/trick", name="trick")
 */
class TrickController extends AbstractController
{
    /**
     * @Route("/create_trick/", name="_create")
     */
    public function editTrick(Request $request)
    {
        $trick = new Trick();
        $form = $this->createForm(TrickFormType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          echo 'test';
        }
        return $this->render('tricks/edit_trick.html.twig', [
          'form' => $form->createView()
        ]);
    }
}
