<?php

namespace MusicBundle\Controller;

use MusicBundle\Data\Data;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DownloadController extends Controller
{
    public function downloadAction(Request $request)
    {
        $key = $request->query->get('key');
        $path = $request->query->get('path');

        $media = $this->getDoctrine()->getEntityManager()
            ->getRepository('MusicBundle\Entity\MediaFile')
            ->findOneByPath($path);

        $path = Data::UPLOAD_DIR . '/' . $path;

        if (!$media || !file_exists($path)) {
            throw $this->createNotFoundException('File not found');
        }

        $filename = $media->getName() . '.' . @end(explode('.', $path));

        $response = new BinaryFileResponse($path);
        $response->trustXSendfileTypeHeader();
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );

        return $response;
    }
}
