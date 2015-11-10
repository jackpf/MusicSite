<?php

namespace Application\Sonata\AdminBundle\Admin;

use Doctrine\ORM\EntityManagerInterface;
use MusicBundle\Data\Data;
use MusicBundle\Service\AudioProcessor;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ReleaseAdmin extends MediaAdmin
{
    private $em;

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
            ->add('releaseVariants', 'sonata_type_collection', [
                'type' => 'sonata_type_admin',
                'required' => true,
                'cascade_validation' => true,
            ], [
                'edit' => 'inline',
                'inline' => 'table',
                'sortable' => 'position',
            ])
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

    public function setEntityManager(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function postPersist($object)
    {
        parent::postPersist($object);

        $this->processAudio($object);
    }

    public function postUpdate($object)
    {
        parent::postUpdate($object);

        $this->processAudio($object);
    }

    protected function processAudio($object)
    {
        foreach ($object->getMediaFiles() as $file) {
            if ($file->getFile()) { // File is set after MediaFile has processed uploaded lossless file
                AudioProcessor::process(
                    $file,
                    150,
                    2,
                    'watermark.mp3',
                    15
                );
            }
        }

        $this->em->flush();
    }
}