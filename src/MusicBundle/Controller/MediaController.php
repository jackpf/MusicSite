<?php

namespace MusicBundle\Controller;

use MusicBundle\Data\Data;
use MusicBundle\Entity\MixItem;
use MusicBundle\Entity\ReleaseItem;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class MediaController extends Controller
{
    public function playAction(Request $request, $id, $token)
    {
        $file = $this->getDoctrine()->getEntityManager()
            ->getRepository('MusicBundle\Entity\MediaFile')
            ->find($id);

        if (!$file) {
            throw $this->createNotFoundException('File not found');
        }

        if (!$this->get('music.token_manager')->consumeToken($file, $token)) {
            throw $this->createAccessDeniedException('Invalid token');
        }

        $response = new BinaryFileResponse(Data::$UPLOAD_PATH . '/' . $file->getPreviewPath());
        $response->trustXSendfileTypeHeader();
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $file->getPreviewPath(),
            iconv('UTF-8', 'ASCII//TRANSLIT', $file->getPreviewPath())
        );

        return $response;
    }

    public function downloadAction(Request $request, $id)
    {
        $downloadManager = $this->get('music.download_manager');
        $key = $request->query->get('key');

        $item = $this->getDoctrine()->getEntityManager()
            ->getRepository('MusicBundle\Entity\MediaItem')
            ->find($id);

        if (!$item) {
            throw $this->createNotFoundException('Media not found');
        }

        list($path, $filename) = $downloadManager->getPath($item);

        $response = new BinaryFileResponse($path);
        $response->trustXSendfileTypeHeader();
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
        $response->headers->set('Content-Description', 'File Transfer');
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Length', filesize($path));

        return $response;
    }
}
