<?php

namespace Application\Sonata\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use MusicBundle\Entity\Data\ReleaseVariantTypes;

class ReleaseVariantAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('type', null, ['property' => 'name'])
            ->add('price')
            ->add('isAvailable', null, ['required' => false])
        ;
    }
}