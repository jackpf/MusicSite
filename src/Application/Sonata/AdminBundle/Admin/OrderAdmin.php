<?php

namespace Application\Sonata\AdminBundle\Admin;

use MusicBundle\Entity\Order;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class OrderAdmin extends Admin
{
    /**
     * Default Datagrid values
     *
     * @var array
     */
    protected $datagridValues = array(
        '_page' => 1,            // display the first page (default = 1)
        '_sort_order' => 'DESC', // reverse order (default = 'ASC')
        '_sort_by' => 'createdAt'  // name of the ordered field
    );

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('status', null, [], 'choice', ['choices' => ['authorized' => 'authorized']])
            ->add('dispatchStatus', null, [], 'choice', ['choices' => [
                Order::DISPATCH_STATUS_PROCESSING => Order::$DISPATCH_STATUS[Order::DISPATCH_STATUS_PROCESSING],
                Order::DISPATCH_STATUS_DISPATCHED => Order::$DISPATCH_STATUS[Order::DISPATCH_STATUS_DISPATCHED],
                Order::DISPATCH_STATUS_UNDISPATCHABLE => Order::$DISPATCH_STATUS[Order::DISPATCH_STATUS_UNDISPATCHABLE],
            ]])
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('releaseVariant')
            ->add('user')
            ->add('status')
            ->add('dispatchStatusString', null, ['label' => 'Dispatch Status'])
            ->add('createdAt')
        ;
    }
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('releaseVariant.mediaItem')
            ->add('releaseVariant.type')
            ->add('user')
            ->add('price')
            ->add('status')
            ->add('details')
            ->add('createdAt')
        ;
    }

    public function getBatchActions()
    {
        $actions = parent::getBatchActions();

        $actions['dispatch'] = array(
            'label'            => 'Mark as dispatched',
            'ask_confirmation' => true, // by default always true
        );

        return $actions;
    }
}