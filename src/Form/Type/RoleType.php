<?php


namespace NS\SimpleUserBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoleType extends AbstractType
{
    /**
     * @var array
     */
    protected $system_roles;

    public function __construct(array $system_roles)
    {
        $this->system_roles = $system_roles;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $options = [];
        foreach($this->system_roles as $role => $label)
        {
            $options[$label] = $role;
        }

        $resolver->setDefaults([
            'choices' => $options,
            'expanded' => true,
            'multiple' => true
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
