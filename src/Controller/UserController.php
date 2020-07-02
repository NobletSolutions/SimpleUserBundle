<?php


namespace NS\SimpleUserBundle\Controller;


use NS\FlashBundle\Service\Messages;
use NS\SimpleUserBundle\Entity\User\Exception\UserNotFoundException;
use NS\SimpleUserBundle\Form\ResetPasswordType;
use NS\SimpleUserBundle\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    /**
     * @var UserService
     */
    protected $user_service;

    /**
     * @var Messages
     */
    protected $flash;

    /**
     * UserController constructor.
     *
     * @param UserService $user_service
     * @param Messages    $flash
     */
    public function __construct(UserService $user_service, Messages $flash)
    {
        $this->user_service = $user_service;
        $this->flash = $flash;
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function changePasswordAction(Request $request)
    {
        $form = $this->createForm(ResetPasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            if ($form->isValid())
            {
                try
                {
                    $this->user_service->updateUserPassword($this->getUser()->getUsername(),
                                                            $form->get('password')->getData());
                    $this->flash->addSuccess(null, null,
                                             "Password updated! You can now log in with your new password.");
                    return $this->redirect($this->generateUrl('dashboard'));
                }
                catch(UserNotFoundException $e)
                {
                    $this->flash->addError(null, null, 'Unable to update password - user not found.');
                }
            }
            else
            {
                $this->flash->addError(null, null, "Some required information was missing");
            }
        }

        return $this->render('@NSSimpleUser/User/change_password.html.twig', ['form' => $form->createView()]);
    }

    public function resetPasswordAction(Request $request, $token)
    {
        $form = $this->createForm(ResetPasswordType::class);

        $user = null;

        try
        {
            $user = $this->user_service->findByResetToken($token);
        }
        catch (UserNotFoundException $e)
        {
            $this->flash->addError(null, 'Error', "That token is no longer valid.");
        }

        if ($user)
        {
            $form->handleRequest($request);

            if ($form->isSubmitted())
            {
                if ($form->isValid())
                {
                    try
                    {
                        $this->user_service->updateUserPassword($user->getUsername(), $form->get('password')->getData());
                        $this->flash->addSuccess(null, 'Success', "Password updated!");
                        return $this->redirect($this->generateUrl('login'));
                    }
                    catch(UserNotFoundException $exception)
                    {
                        $this->flash->addError(null, null, 'Unable to update password - user not found.');
                    }
                }
                else
                {
                    $this->flash->addError(null, 'Error', 'Your passwords did not match.');
                }
            }
        }

        return $this->render('@NSSimpleUser/User/reset_password.html.twig', ['form' => $form->createView(), 'token' => $token]);
    }
}
