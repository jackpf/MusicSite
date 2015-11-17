<?php

namespace MusicBundle\Service;

use MusicBundle\Entity\MediaFile;
use MusicBundle\Data\Data;
use Doctrine\ORM\EntityManagerInterface;

class VideoProcessor extends Processor
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function process(MediaFile $file)
    {
        $audioPath = $file->getAudioPath();
        $videoPath = $file->getVideoPath();
        $processedPath = DownloadManager::createPath($videoPath, 'mp4');

        $result = self::run(sprintf(
            'ffmpeg -itsoffset %f -i %s -i %s -strict -2 %s -c:v libx264 -preset ultrafast -crf 23 -y -movflags +faststart',
            (float) $file->getAudioDelay(),
            Data::getUploadPath() . '/' . $audioPath,
            Data::getUploadPath() . '/' . $videoPath,
            Data::getUploadPath() . '/' . $processedPath
        ));

        if ($file->getProcessedPath() != null) {
            $file->delete($file->getProcessedPath());
        }

        $file->setProcessedPath($processedPath);

        $iconPath = DownloadManager::createPath($processedPath, 'jpg');

        self::run(sprintf(
            'ffmpeg -ss 1.0 -i %s -vframes 1 -s 640x480 -f image2 %s',
            Data::getUploadPath() . '/' . $processedPath,
            Data::getUploadPath() . '/' . $iconPath
        ));

        if ($file->getIconPath() != null) {
            $file->delete($file->getIconPath());
        }

        $file->setIconPath($iconPath);

        $this->em->flush();

        return $result;
    }
}