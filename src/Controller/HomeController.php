<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function index(TrickRepository $trickRepository, PaginatorInterface $paginator)
    {

      $tricks = $trickRepository->findAll();

      $listTricks = $paginator->paginate(
				$tricks,
				1,
				4
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
	 * @return JsonResponse
	 */
    public function loadMoreTricks(TrickRepository $trickRepository, PaginatorInterface $paginator, Request $request, $page) {

			if($request->isXmlHttpRequest()) {

				$query = $trickRepository->findAll();

				$tricks = $paginator->paginate(
					$query,
					$page,
					4
				);

				$index = 0;
				$trickAr = [];
				foreach ($tricks->getItems() as $trick) {
					$trickAr[$index]['id'] = $trick->getId();
					$trickAr[$index]['name'] = $trick->getName();
					$trickAr[$index]['slug'] = $trick->getSlug();
					$trickAr[$index]['picture'] = $trick->getPicture();
					$index++;
				}

				return new JsonResponse($trickAr);
			}
		}
}
