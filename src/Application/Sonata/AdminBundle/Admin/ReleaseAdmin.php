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

    private $ap;

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
            ->add('mediaVariants', 'sonata_type_collection', [
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

        $this->createPreview($object);
    }

    public function postUpdate($object)
    {
        parent::postUpdate($object);

        $this->createPreview($object);
    }

    public function createPreview($object)
    {
        foreach ($object->getMediaFiles() as $file) {
            if ($file->getPreviewFile()) {
                $parts = explode('.', $file->getPath());
                $previewPath = $parts[0] . '-preview.' . $parts[1];

                AudioProcessor::process(
                    Data::getUploadPath() . '/' . $file->getPath(),
                    Data::getUploadPath() . '/' . $previewPath,
                    150,
                    2,
                    Data::getUploadPath() . '/watermark.mp3',
                    15
                );

                $file->setPreviewPath($previewPath);
            }
        }

        $this->em->flush();
    }
}