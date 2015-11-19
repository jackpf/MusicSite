<?php

namespace MusicBundle\Controller;

use MusicBundle\Data\Data;
use MusicBundle\Entity\AudioFile;
use MusicBundle\Entity\MixItem;
use MusicBundle\Entity\ReleaseItem;
use MusicBundle\Entity\VideoFile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use JMS\SecurityExtraBundle\Security\Authorization\Expression\Expression;

class MediaController extends Controller
{
    public function playAction(Request $request, $id, $token)
    {
        $file = $this->getDoctrine()->getEntityManager()
            ->getRepository('MusicBundle:MediaFile')
            ->find($id);

        if (!$file) {
            throw $this->createNotFoundException('File not found');
        }

        if (!$this->get('music.token_manager')->consumeToken($file, $token)) {
            throw $this->createAccessDeniedException('Invalid token');
        }

        if ($file instanceof AudioFile) {
            $path = $file->getPreviewPath();
        } else if ($file instanceof VideoFile) {
            $path = $file->getProcessedPath();
        }

        $response = new BinaryFileResponse(Data::getUploadPath() . '/' . $path);
        $response->trustXSendfileTypeHeader();
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $path,
            iconv('UTF-8', 'ASCII//TRANSLIT', $path)
        );

        return $response;
    }

    public function downloadAction(Request $request, $id, $type = '320')
    {
        $downloadManager = $this->get('music.download_manager');
        $key = $request->query->get('key');

        $item = $this->getDoctrine()->getEntityManager()
            ->getRepository('MusicBundle:MediaItem')
            ->find($id);

        if (!$item) {
            throw $this->createNotFoundException('Media not found');
        }

        list($path, $filename) = $downloadManager->getPath($item, $type);

        $response = new Response();

        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', mime_content_type($path));
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '";');
        $response->headers->set('Content-length', filesize($path));

        $response->setContent(file_get_contents($path));

        return $response;
    }

    /**
     * @PreAuthorize("hasRole('A') or (hasRole('B') and hasRole('C'))")
     */
    public function favouriteAction($id)
    {
        $item = $this->getDoctrine()->getEntityManager()
            ->getRepository('MusicBundle:MediaItem')
            ->find($id);

        if (!$item) {
            throw $this->createNotFoundException('Media not found');
        }

        $user = $this->get('security.token_storage')
            ->getToken()
            ->getUser();

        if (!$user->hasFavourite($item)) {
            $user->addFavourite($item);
        } else {
            $user->removeFavourite($item);
        }

        $this->getDoctrine()->getManager()
            ->flush();

        return new RedirectResponse($this->get('music.twig.helper_extension')->itemPath($item));
    }
}
