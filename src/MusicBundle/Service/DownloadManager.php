<?php

namespace MusicBundle\Service;

use MusicBundle\Data\Data;
use MusicBundle\Entity\MediaItem;
use MusicBundle\Entity\MixItem;
use MusicBundle\Entity\ReleaseItem;

class DownloadManager
{
    public static function createName($path, $name)
    {
        return $name . substr($path, strrpos($path, '.'));
    }

    public static function createPath($original)
    {
        $parts = explode('.', $original);
        $ext = end($parts);
        return sha1($original + rand()) . '.' . $ext;
    }

    public function getPath(MediaItem $item, $type = null)
    {
        if ($item instanceof ReleaseItem) {
            return $this->getReleasePath($item, $type);
        } else if ($item instanceof MixItem) {
            return $this->getMixPath($item, $type);
        } else {
            throw new \RuntimeException(sprintf('Unsupported media: "%s"', get_class($item)));
        }
    }

    public function getReleasePath(ReleaseItem $item, $type)
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
            if ($type == '320') {
                $path = $file->getPath();
            } else if ($type == 'lossless') {
                $path = $file->getLosslessPath();
            } else {
                throw new \RuntimeException(sprintf('Unsupported type: "%s"', $type));
            }

            $zip->addFile(
                Data::getUploadPath() . '/' . $path,
                self::createName($file->getPath(), $file->getName())
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
            Data::getUploadPath() . '/' . $file->getPath(),
            self::createName($file->getPath(), $file->getName())
        ];
    }
}