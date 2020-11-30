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
    protected $env;

    public function __construct(Mailer $mailer, string $env)
    {
        $this->mailer = $mailer;
        $this->env    = $env;
    }

    /**
     * @param UserEvent $event
     */
    public function onUserPasswordReset(UserEvent $event): void
    {
        try {
            $user = $event->getUser();

            $this->mailer->sendPasswordReset($user, $user->getResetToken());
        } catch (\Exception $exception) {
            //we don't want email-related problems stopping anything
            if ($this->env === 'dev') {
                throw $exception;
            }
        }
    }

    /**
     * @return array|string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            UserEvent::PASSWORD_RESET => 'onUserPasswordReset',
        ];
    }
}
