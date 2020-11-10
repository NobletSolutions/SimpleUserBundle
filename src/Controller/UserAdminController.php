<?php


namespace NS\SimpleUserBundle\Controller;


use NS\AdminBundle\Controller\AbstractAdminController;
use NS\AdminBundle\Exception\AdminActionFailedException;
use NS\SimpleUserBundle\Entity\User\User;
use NS\SimpleUserBundle\Form\EditType;
use NS\SimpleUserBundle\Service\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class UserAdminController extends AbstractAdminController
{
    protected $templates;

    /**
     * @var AuthorizationCheckerInterface
     */
    protected $authorization_checker;

    /**
     * @return string
     */
    protected function getEditFormClass(): string
    {
        return EditType::class;
    }

    /**
     * UserAdminController constructor.
     *
     * @param UserService                   $admin_service
     * @param AuthorizationCheckerInterface $authorization_checker
     * @param array                         $templates
     */
    public function __construct(UserService $admin_service, AuthorizationCheckerInterface $authorization_checker, array $templates)
    {
        parent::__construct($admin_service);
        $this->templates             = $templates;
        $this->authorization_checker = $authorization_checker;
    }

    public function getListTemplate(): string
    {
        return $this->templates['admin_list'];
    }

    public function getCreateTemplate(): string
    {
        return $this->templates['admin_create'];
    }

    public function getEditTemplate(): string
    {
        return $this->templates['admin_edit'];
    }

    public function getViewTemplate(): string
    {
        return $this->templates['admin_view'];
    }

    /**
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sendPasswordResetAction(Request $request, $id)
    {
        /**
         * @var User $user
         */
        $user = $this->getRecord($id);

        if(!$this->authorization_checker->isGranted('ROLE_SUPER_ADMIN'))
        {
            throw new AdminActionFailedException('You are not authorized to edit users.');
        }

        try
        {
            $this->admin_service->issuePasswordReset($user->getUsername());
            $this->flash->addSuccess(null, 'Password reset sent.');
        }
        catch(UsernameNotFoundException $e)
        {
            $this->flash->addError(null, 'User not found');
        }

        return $this->redirectToDefault();
    }
}
