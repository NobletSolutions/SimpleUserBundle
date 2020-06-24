<?php


namespace NS\SimpleUserBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('_username', TextType::class, ['vertical' => true, 'required'=>true, 'label'=>'Email address']);
        $builder->add('_password', PasswordType::class, ['vertical' => true, 'required'=>true]);
    }

    public function getBlockPrefix(): ?string
    {
        return ''; //Firewall is expecting "_username", not "login_form[_username]"
    }
}
