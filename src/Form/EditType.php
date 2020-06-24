<?php


namespace NS\SimpleUserBundle\Form;

use NS\SimpleUserBundle\Entity\User\User;
use NS\SimpleUserBundle\Form\Type\RoleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class EditType extends AbstractType
{
    /**
     * @var AuthorizationCheckerInterface
     */
    protected $authorization_checker;

    public function __construct(AuthorizationCheckerInterface $authorization_checker)
    {
        $this->authorization_checker = $authorization_checker;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('name')
            ->add('email', EmailType::class);

        if($this->authorization_checker->isGranted('ROLE_SUPER_ADMIN'))
        {
            $builder->add('roles', RoleType::class);
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}
