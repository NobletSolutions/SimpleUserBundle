<?php


namespace NS\SimpleUserBundle\Subscriber;

use NS\SimpleUserBundle\Entity\User\Event\UserEvent;
use NS\SimpleUserBundle\Service\Mailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PasswordResetSubscriber implements EventSubscriberInterface
{
    /**
     * @var Mailer
     */
    protected $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param UserEvent $event
     */
    public function onUserPasswordReset(UserEvent $event): void
    {
        $user = $event->getUser();

        $this->mailer->sendPasswordReset($user, $user->getResetToken());
    }

    /**
     * @return array|string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            UserEvent::PASSWORD_RESET => 'onUserPasswordReset'
        ];
    }
}
