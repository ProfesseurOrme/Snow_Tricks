<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("", name="app")
 */
class SecurityController extends AbstractController
{
    private $translator;

    public function __construct(TranslatorInterface $translator) {
      $this->translator = $translator;
    }

	/**
	 * @Route("/login", name="_login")
	 * @param AuthenticationUtils $authenticationUtils
	 * @param AuthorizationCheckerInterface $authorizationChecker
	 * @return Response
	 */
    public function login(AuthenticationUtils $authenticationUtils, AuthorizationCheckerInterface $authorizationChecker): Response
    {
        if ($this->getUser() || $authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
          return $this->redirectToRoute('home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

  /**
   * @Route("registration", name="_registration")
   * @param UserPasswordEncoderInterface $passwordEncoder
   * @param GuardAuthenticatorHandler $guardHandler
   * @param LoginFormAuthenticator $authenticator
   * @return Response
   */
  public function register(Request $request,UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler
$guardHandler,
		LoginFormAuthenticator $authenticator): Response
  {
    $user = new User();
    $form = $this->createForm(RegistrationFormType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

      $user->setPassword(
        $passwordEncoder->encodePassword(
          $user,
          $form->get('plainPassword')->getData()
        )
      );

      $entityManager = $this->getDoctrine()->getManager();
      $user->setRoles(['ROLE_USER']);
      $user->setIsActive(true);
      $user->setIsVerified(false);
      $entityManager->persist($user);
      $entityManager->flush();
      // do anything else you need here, like send an email

      return $guardHandler->authenticateUserAndHandleSuccess(
        $user,
        $request,
        $authenticator,
        'main'
      );
    }

    return $this->render('security/register.html.twig', [
      'registrationForm' => $form->createView(),
    ]);
  }

	/**
	 * @Route("reset-password", name="_reset_password")
	 * @param Request $request
	 * @param UserPasswordEncoderInterface $passwordEncoder
	 * @param AuthorizationCheckerInterface $authorizationChecker
	 * @param UserRepository $userRepository
	 * @return Response
	 */
    public function resetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder,
      AuthorizationCheckerInterface $authorizationChecker, UserRepository $userRepository) {

      if($authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {

        if($request->isMethod('POST')){

          $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);

          if($user->getEmail() == $request->get('email') && $this->isCsrfTokenValid('authenticate',
							$request->get('token'))) {

            $user->setPassword(
              $passwordEncoder->encodePassword(
                $user,
                $request->request->get('password')
              )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
          }
        }
        return $this->render('security/reset-password.html.twig');
      } else {
        return $this->redirectToRoute(('home'));
      }
    }

	/**
	 * @Route("/search-this-username", name="_search_user")
	 * @param UserRepository $userRepository
	 * @param Request $request
	 * @return JsonResponse
	 */
    public function searchUser(UserRepository $userRepository, Request $request) {
    	if($request->isXmlHttpRequest()) {

    		$users = $userRepository->findAll();
				foreach ($users as $user) {

					if( strcasecmp($request->get('username'), $user->getUsername()) == 0 ) {
						return new JsonResponse('true', Response::HTTP_NOT_FOUND);
					}
				}
				return new JsonResponse('false');
			}
		}

    /**
     * @Route("forgot-password", name="_forgot_password")
     */
    public function forgotPassword() {

    }

    /**
     * @Route("logout", name="_logout")
     */
    public function logout()
    {
        $this->redirectToRoute('home');
    }
}
