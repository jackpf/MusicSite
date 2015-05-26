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
            ->add('mediaFiles', 'collection', [
                'type' => new MediaFileType(),
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
            ])
            ->add('mediaVariants', 'collection', [
                'type' => new MediaVariantType(),
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
                'cascade_validation' => true,
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

    public function setAudioProcessor(AudioProcessor $ap)
    {
        $this->ap = $ap;
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

                $this->ap->trim(
                    Data::getUploadPath() . '/' . $file->getPath(),
                    Data::getUploadPath() . '/' . $previewPath,
                    120,
                    2
                );

                $file->setPreviewPath($previewPath);
            }
        }

        $this->em->flush();
    }
}