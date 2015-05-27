<?php

namespace Application\Sonata\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use MusicBundle\Entity\Data\MediaVariantTypes;

class MediaVariantAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('type', 'choice', ['choices' => MediaVariantTypes::$TYPES])
            ->add('price')
            ->add('isAvailable', null, ['required' => false])
        ;
    }
}