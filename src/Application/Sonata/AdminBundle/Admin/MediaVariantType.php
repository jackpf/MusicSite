<?php

namespace Application\Sonata\AdminBundle\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use MusicBundle\Entity\Data\MediaVariantTypes;

class MediaVariantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'choice', ['choices' => MediaVariantTypes::$TYPES])
            ->add('price')
            ->add('isAvailable', null, ['required' => false])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MusicBundle\Entity\MediaVariant'
        ));
    }

    public function getName()
    {
        return 'media_variant';
    }
}