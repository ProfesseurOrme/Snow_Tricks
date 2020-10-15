<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\Translator;

/**
 * @Route("", name="home")
 */
class HomeController extends AbstractController
{
	/**
	 * @Route("/", options={"expose"=true},name="")
	 * @param TrickRepository $trickRepository
	 * @param PaginatorInterface $paginator
	 * @return Response
	 */
    public function index(TrickRepository $trickRepository, PaginatorInterface $paginator, Request $request)
    {

			$translator = new Translator($request->getLocale());

			dump($translator->trans('Connexion_Success'));
      $tricks = $trickRepository->findAll();

      $listTricks = $paginator->paginate(
				$tricks,
				1,
				8
			);

      return $this->render('home/index.html.twig', [
          'controller_name' => 'HomeController',
          'tricks' => $listTricks,
					'nbTricks' => $listTricks->getTotalItemCount()
      ]);
    }

	/**
	 * @Route("/load-page-{page}", options={"expose"=true},name="_load_more")
	 * @param TrickRepository $trickRepository
	 * @param PaginatorInterface $paginator
	 * @param Request $request
	 * @param $page
	 * @return Response
	 */
    public function loadMoreTricks(TrickRepository $trickRepository, PaginatorInterface $paginator, Request $request, $page) {

			if($request->isXmlHttpRequest()) {

				$query = $trickRepository->findAll();

				$tricks = $paginator->paginate(
					$query,
					$page,
					8
				);

				return $this->render('assets/card_trick.html.twig', [
					'tricks' => $tricks,
					'nbTricks' => $tricks->getTotalItemCount()
				]);
			} else {
				return new JsonResponse('no');
			}
		}
}
