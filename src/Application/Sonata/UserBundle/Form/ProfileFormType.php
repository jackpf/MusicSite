<?php

namespace Application\Sonata\UserBundle\Form;

use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildUserForm($builder, $options);

        $builder->add('plainPassword', 'password', ['label' => 'Password']);
    }
}