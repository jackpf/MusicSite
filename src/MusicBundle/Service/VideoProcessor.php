<?php

namespace MusicBundle\Service;

use MusicBundle\Entity\MediaFile;
use MusicBundle\Data\Data;

class VideoProcessor extends Processor
{
    public function process(MediaFile $file)
    {
        $path = $file->getPath();
        $processedPath = DownloadManager::createPath($path, 'mp4');

        $result = self::run(sprintf(
            'ffmpeg -i %s -strict -2 %s -preset ultrafast -crf 23 -y -movflags +faststart',
            Data::getUploadPath() . '/' . $path,
            Data::getUploadPath() . '/' . $processedPath
        ));

        if ($file->getProcessedPath() != null) {
            $file->delete($file->getProcessedPath());
        }

        $file->setProcessedPath($processedPath);

        $iconPath = DownloadManager::createPath($file->getPath(), 'jpg');

        self::run(sprintf(
            'ffmpeg -ss 1.0 -i %s -vframes 1 -s 640x480 -f image2 %s',
            Data::getUploadPath() . '/' . $file->getPath(),
            Data::getUploadPath() . '/' . $iconPath
        ));

        if ($file->getIconPath() != null) {
            $file->delete($file->getIconPath());
        }

        $file->setIconPath($iconPath);

        $audioPath = DownloadManager::createPath($file->getPath(), 'mp3');

        self::run(sprintf(
            'ffmpeg -i %s %s',
            Data::getUploadPath() . '/' . $file->getPath(),
            Data::getUploadPath() . '/' . $audioPath
        ));

        if ($file->getAudioPath() != null) {
            $file->delete($file->getAudioPath());
        }

        $file->setAudioPath($audioPath);

        return $result;
    }
}
