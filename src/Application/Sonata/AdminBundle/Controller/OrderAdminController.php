<?php

namespace Application\Sonata\AdminBundle\Controller;

use MusicBundle\Entity\Order;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
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

        $i = 0;

        try {
            foreach ($selectedModels as $selectedModel) {
                if ($selectedModel->getDispatchStatus() == Order::DISPATCH_STATUS_PROCESSING) {
                    $selectedModel->setDispatchStatus(Order::DISPATCH_STATUS_DISPATCHED);

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

        $this->addFlash('sonata_flash_success', sprintf('Marked %d orders as dispatched', $i));

        return new RedirectResponse(
            $this->admin->generateUrl('list', $this->admin->getFilterParameters())
        );
    }
}