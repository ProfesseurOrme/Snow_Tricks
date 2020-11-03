<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use App\Services\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("", name="app")
 */
class SecurityController extends AbstractController
{
    private $translator;
    private $mailerService;
    private $entityManager;

    public function __construct(TranslatorInterface $translator, MailerService $mailerService, EntityManagerInterface
		$entityManager) {
      $this->translator = $translator;
      $this->mailerService = $mailerService;
      $this->entityManager = $entityManager;
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
	 * @param Request $request
	 * @param UserPasswordEncoderInterface $passwordEncoder
	 * @param GuardAuthenticatorHandler $guardHandler
	 * @param LoginFormAuthenticator $authenticator
	 * @return Response
	 * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
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
      $user->setIsVerified(false);
      $entityManager->persist($user);
      $entityManager->flush();

      $url = $this->generateUrl('app_enable_user', [
      	'username' => $user->getUsername()
			], UrlGeneratorInterface::ABSOLUTE_URL) ;
      $this->mailerService->sendMail($user, $url, 'emails/register.html.twig');

			$this->addFlash(
				'info',
				$this->translator->trans('User_Signup_Success')
			);

			return $this->redirectToRoute('home');
    }

    return $this->render('security/register.html.twig', [
      'registrationForm' => $form->createView(),
    ]);
  }

	/**
	 * @Route("reset-password/{username}/{token}", name="_reset_password")
	 * @param Request $request
	 * @param UserPasswordEncoderInterface $passwordEncoder
	 * @param UserRepository $userRepository
	 * @param $username
	 * @param $token
	 * @return Response
	 */
    public function resetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder, UserRepository
		$userRepository, $username, $token) {

			if($request->isMethod('POST')){

				$user = $userRepository->findOneBy(['username' => $username, 'resetToken' => $token]);

				dump($user);

				if($this->isCsrfTokenValid('authenticate', $request->request->get('token')) && $user) {


					$user->setPassword(
						$passwordEncoder->encodePassword(
							$user,
							$request->request->get('password')
						)
					);

					$entityManager = $this->getDoctrine()->getManager();
					$entityManager->persist($user);
					$entityManager->flush();

					$this->addFlash(
						'info',
						$this->translator->trans('User_Reset_Account_Valid')
					);

					return $this->redirectToRoute('home');
				}
			}
			return $this->render('security/reset-password.html.twig');
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
	 * @param Request $request
	 * @param TokenGeneratorInterface $tokenGenerator
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
	 */
    public function forgotPassword(Request $request, TokenGeneratorInterface $tokenGenerator) {

    	if($request->isMethod('POST')) {

				$submittedToken = $request->request->get('token');
				if ($this->isCsrfTokenValid('authenticate', $submittedToken)) {

					$user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $request->get
					('username'),'email' => $request->get('email')]);
					if($user) {

						$token = $tokenGenerator->generateToken();
						$user->setResetToken($token);
						$url = $this->generateUrl('app_reset_password', [
							'username' => $user->getUsername(),
							'token' => $token
						], UrlGeneratorInterface::ABSOLUTE_URL) ;

						$this->mailerService->sendMail($user, $url, 'emails/reset-password.html.twig');

						$this->entityManager->persist($user);
						$this->entityManager->flush();

						$this->addFlash(
							'info',
							$this->translator->trans('User_Reset_Account_Success')
						);

						return $this->redirectToRoute('home');

					} else {
						$this->addFlash(
							'error',
							$this->translator->trans('User_Reset_Account_Error')
						);
					}
				}
			}

    	return $this->render('security/reset-password-verifymail.html.twig');

    }

	/**
	 * @Route("enable-this-user/{username}", name="_enable_user")
	 * @param Request $request
	 * @param $username
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
		public function enableUser(Request $request, $username) {

			if ($request->isMethod('POST')) {
				$userDisabled = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);

				$userDisabled->setIsVerified(true);

				$this->entityManager->persist($userDisabled);
				$this->entityManager->flush();

				$this->addFlash(
					'info',
					$this->translator->trans('User_Verify_Account_Success')
				);

				return $this->redirectToRoute('home');
			}
		}

    /**
     * @Route("logout", name="_logout")
     */
    public function logout()
    {
        $this->redirectToRoute('home');
    }
}
