<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 26/08/16
 * Time: 3:39 PM
 */

namespace NS\SimpleUserBundle\Service;

use NS\SimpleUserBundle\Entity\User\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class Mailer
{
    /**
     * @var Environment
     */
    protected $twig;

    /**
     * @var MailerInterface
     */
    protected $mailer;

    /**
     * @var string
     */
    protected $ns_simple_user_email_sender;

    /**
     * @var string
     */
    protected $email_recipient;

    /**
     * @var UserService
     */
    protected $user_service;

    /**
     * @var string
     */
    protected $ns_simple_user_password_reset_subject;

    /**
     * Mailer constructor.
     *
     * @param Environment     $twig
     * @param MailerInterface $mailer
     * @param UserService     $user_service
     * @param string|null     $ns_simple_user_email_sender
     * @param string          $ns_simple_user_password_reset_subject
     */
    public function __construct(Environment $twig, MailerInterface $mailer, UserService $user_service, string $ns_simple_user_email_sender = '', string $ns_simple_user_password_reset_subject = '')
    {
        $this->twig                                  = $twig;
        $this->ns_simple_user_email_sender           = $ns_simple_user_email_sender;
        $this->mailer                                = $mailer;
        $this->user_service                          = $user_service;
        $this->ns_simple_user_password_reset_subject = $ns_simple_user_password_reset_subject;
    }

    public function sendPasswordReset(User $user, $token)
    {
        $subject = $this->ns_simple_user_password_reset_subject;
        $message = (new Email())
            ->from($this->ns_simple_user_email_sender)
            ->to($user->getEmail())
            ->subject($subject)
            ->html(
                $this->twig->render('@NSSimpleUser/Email/reset.html.twig',
                                    ['user' => $user, 'token' => $token, 'subject' => $subject]),
                'text/html'
            );

        $this->mailer->send($message);
    }
}
