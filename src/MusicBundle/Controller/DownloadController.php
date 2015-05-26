<?php

namespace MusicBundle\Controller;

use MusicBundle\Data\Data;
use MusicBundle\Entity\MixItem;
use MusicBundle\Entity\ReleaseItem;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DownloadController extends Controller
{
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
