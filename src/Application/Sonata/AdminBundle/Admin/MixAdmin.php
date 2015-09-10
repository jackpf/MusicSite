<?php

namespace Application\Sonata\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class MixAdmin extends MediaAdmin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper
            ->add('mediaFiles', 'sonata_type_collection', [
                'type' => 'sonata_type_admin',
                'required' => true,
                'cascade_validation' => true,
            ], [
                'edit' => 'inline',
                'inline' => 'table',
                'sortable' => 'position',
            ])
            ->add('downloadLink', null)
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        parent::configureDatagridFilters($datagridMapper);
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);
    }

    public function postPersist($object)
    {
        parent::postPersist($object);

        $this->createPreview($object);
    }

    public function postUpdate($object)
    {
        parent::postUpdate($object);

        $this->createPreview($object);
    }

    public function createPreview($object)
    {
        // For mixes, the preview path is gonna be the actual path
        foreach ($object->getMediaFiles() as $file) {
            $file->setPreviewPath($file->getPath());
        }
    }
}