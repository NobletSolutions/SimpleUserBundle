<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 06/06/16
 * Time: 12:48 PM.
 */
namespace NS\SimpleUserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options' => ['label' => 'simpleuser.form.new_password'],
            'second_options' => ['label' => 'simpleuser.form.confirm_new_password'],
        ]);
    }
}
