<?php

namespace MusicBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use MusicBundle\Entity\MediaFile;
use MusicBundle\Entity\MediaItem;
use MusicBundle\Entity\MixItem;
use MusicBundle\Entity\PlayToken;
use MusicBundle\Entity\ReleaseItem;
use MusicBundle\Entity\VideoCastItem;

class PlayTokenManager
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createTokens(MediaItem $item)
    {
        $tokens = [];

        if (!($item instanceof ReleaseItem || $item instanceof MixItem || $item instanceof VideoCastItem)) {
            return $tokens;
        }

        foreach ($item->getMediaFiles() as $file) {
            $tokens[$file->getId()] = new PlayToken($file);
            $this->em->persist($tokens[$file->getId()]);
        }

        $this->em->flush();

        return $tokens;
    }

    public function createToken(MediaFile $file)
    {
        $token = new PlayToken($file);
        $this->em->persist($token);
        $this->em->flush();

        return $token;
    }

    public function consumeToken(MediaFile $file, $token)
    {
        $expires = new \DateTime('now');
        //$expires->sub(new \DateInterval('PT60S'));

        $token = $this->em->getRepository('MusicBundle\Entity\PlayToken')
            ->createQueryBuilder('t')
            ->select('t')
            ->where('t.mediaFile = :file')
            ->andWhere('t.token = :token')
            //->andWhere('t.createdAt > :expires')
            ->setParameters(['file' => $file, 'token' => $token])//, 'expires' => $expires])
            ->getQuery()->getResult()
        ;

        return $token == true;
    }
}