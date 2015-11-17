<?php

namespace Application\Sonata\AdminBundle\Admin;

use MusicBundle\Data\Data;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

abstract class MediaAdmin extends Admin
{
    protected $formOptions = array(
        'cascade_validation' => true,
    );

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title')
            ->add('shortContent', 'textarea')
            ->add('content', 'textarea')
            ->add('imageFile', 'file', [
                'required' => false,
                'label' => 'Image',
                'help' => $this->getSubject()->getImageReal() ? '<img src="/uploads/' . $this->getSubject()->getImageReal() . '" class="admin-preview" />' : '',
            ])
            ->add('backgroundFile', 'file', [
                'required' => false,
                'label' => 'Background',
                'help' => $this->getSubject()->getBackgroundReal() ? '<img src="/uploads/' . $this->getSubject()->getBackgroundReal() . '" class="admin-preview" />' : '',
            ])
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('slug')
        ;
    }
}