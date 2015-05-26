<?php

namespace MusicBundle\Service;

use MusicBundle\Data\Data;
use MusicBundle\Entity\MediaItem;
use MusicBundle\Entity\MixItem;
use MusicBundle\Entity\ReleaseItem;

class DownloadManager
{
    private function createName($path, $name)
    {
        return $name . substr($path, strrpos($path, '.'));
    }

    public function getPath(MediaItem $item)
    {
        if ($item instanceof ReleaseItem) {
            return $this->getReleasePath($item);
        } else if ($item instanceof MixItem) {
            return $this->getMixPath($item);
        } else {
            throw new \RuntimeException('Unsupported media');
        }
    }

    public function getReleasePath(ReleaseItem $item)
    {
        $path = tempnam(sys_get_temp_dir(), 'release');

        if ($path == false) {
            throw new \RuntimeException('Unable to create temp file');
        }

        $zip = new \ZipArchive();

        if (!$zip->open($path, \ZipArchive::OVERWRITE)) {
            throw new \RuntimeException('Unable to create zip file');
        }

        foreach ($item->getMediaFiles() as $file) {
            $zip->addFile(
                Data::$UPLOAD_PATH . '/' . $file->getPath(),
                $this->createName($file->getPath(), $file->getName())
            );
        }

        $zip->close();

        return [
            $path,
            substr($path, strrpos($path, '/') + 1) . '.zip'
        ];
    }

    public function getMixPath(MixItem $item)
    {
        $files = $item->getMediaFiles();

        if (isset($files[0])) {
            $file = $files[0];
        } else {
            throw $this->createNotFoundException('File not found');
        }

        return [
            Data::$UPLOAD_PATH . '/' . $file->getPath(),
            $this->createName($file->getPath(), $file->getName())
        ];
    }
}