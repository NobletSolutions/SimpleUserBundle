<?php

namespace NS\SimpleUserBundle\Controller;

use NS\FlashBundle\Service\Messages;
use NS\SimpleUserBundle\Entity\User\Exception\UserNotFoundException;
use NS\SimpleUserBundle\Form\LoginFormType;
use NS\SimpleUserBundle\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    /**
     * @var Messages
     */
    protected $flash;

    public function __construct(Messages $flash)
    {
        $this->flash = $flash;
    }

    /**
     * @param Request             $request
     * @param AuthenticationUtils $authentication_utils
     *
     * @return Response
     */
    public function loginAction(Request $request, AuthenticationUtils $authentication_utils): Response
    {
        $error = $authentication_utils->getLastAuthenticationError();
        if($error instanceof BadCredentialsException)
        {
            $this->flash->addError(null, 'Error', "Incorrect email address or password.");
        }
        else if($error)
        {
            $this->flash->addError(null, 'Error', $error->getMessage());
        }

        $form = $this->createForm(LoginFormType::class);


        return $this->render('@NSSimpleUser/Login/login.html.twig', [
            'last_username' => $authentication_utils->getLastUsername(),
            'error' => $error,
            'form'=> $form->createView(),
        ]);
    }

    /**
     * @param Request     $request
     * @param UserService $user_service
     *
     * @return Response
     */
    public function forgotPasswordAction(Request $request, UserService $user_service): Response
    {
        if ($request->getMethod() === 'POST')
        {
            try
            {
                $username = $request->get('_username');
                $user_service->issuePasswordReset($username);
            }
            catch(UserNotFoundException $e)
            {
                $this->flash->addError(null, '', 'Sorry, no user was found matching that email address.');
                return $this->redirectToRoute('forgotPassword');
            }

            return $this->redirectToRoute('forgot_success');
        }

        return $this->render('@NSSimpleUser/Login/forgot.html.twig');
    }

    public function forgotSuccessAction(Request $request)
    {
        return $this->render('@NSSimpleUser/Login/forgot_success.html.twig');
    }
}
