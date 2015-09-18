<?php

namespace Application\Sonata\AdminBundle\Controller;

use MusicBundle\Entity\Order;
use Payum\Core\Request\GetHumanStatus;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class OrderAdminController extends CRUDController
{
    public function batchActionDispatch(ProxyQueryInterface $selectedModelQuery)
    {
        if(!$this->admin->isGranted('EDIT')) {
            throw new AccessDeniedException();
        }

        $modelManager = $this->admin->getModelManager();
        $selectedModels = $selectedModelQuery->execute();
        $eventDispatcher = $this->get('event_dispatcher');
        $i = 0;

        try {
            foreach ($selectedModels as $selectedModel) {
                if ($selectedModel->getDispatchStatus() == Order::DISPATCH_STATUS_DISPATCHED) {
                    $this->addFlash('sonata_flash_error', sprintf('Order %d has already been dispatched.', $selectedModel->getId()));
                    continue;
                }
                if ($selectedModel->getDispatchStatus() == Order::DISPATCH_STATUS_UNDISPATCHABLE) {
                    $this->addFlash('sonata_flash_error', sprintf('Order %d is not dispatchable.', $selectedModel->getId()));
                    continue;
                }
                if ($selectedModel->getStatus() != GetHumanStatus::STATUS_AUTHORIZED) {
                    $this->addFlash('sonata_flash_error', sprintf('Order %d has not been authorized.', $selectedModel->getId()));
                    continue;
                }

                if ($selectedModel->getDispatchStatus() == Order::DISPATCH_STATUS_PROCESSING) {
                    $selectedModel->setDispatchStatus(Order::DISPATCH_STATUS_DISPATCHED);
                    $eventDispatcher->dispatch('event.dispatch', new GenericEvent(null, ['order' => $selectedModel]));
                    $i++;
                }
            }

            $modelManager->update($selectedModel);
        } catch (\Exception $e) {
            $this->addFlash('sonata_flash_error', 'flash_batch_merge_error');

            return new RedirectResponse(
                $this->admin->generateUrl('list',$this->admin->getFilterParameters())
            );
        }

        if ($i > 0) {
            $this->addFlash('sonata_flash_success', sprintf('Marked %d orders as dispatched.', $i));
        }

        return new RedirectResponse(
            $this->admin->generateUrl('list', $this->admin->getFilterParameters())
        );
    }
}