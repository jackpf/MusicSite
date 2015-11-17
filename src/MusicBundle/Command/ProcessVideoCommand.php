<?php

namespace MusicBundle\Command;

use MusicBundle\Entity\VideoQueueItem;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessVideoCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('process:video')
            ->setDescription('Process next video in the video queue')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        try {
            $queueItem = $em->getRepository('MusicBundle:VideoQueueItem')
                ->createQueryBuilder('i')
                ->select('i')
                ->where('i.state = :unprocessed')
                ->orderBy('i.createdAt', 'ASC')
                ->setMaxResults(1)
                ->setParameter('unprocessed', VideoQueueItem::STATE_UNPROCESSED)
                ->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            $output->writeln('No items in queue');
            return ;
        }

        $processor = $this->getContainer()->get('music.video_processor');
        $result = $processor->process($queueItem->getFile());

        $queueItem->setResult($result);

        if ($result == 0) {
            $queueItem->setState(VideoQueueItem::STATE_PROCESSED);
        } else {
            $queueItem->setState(VideoQueueItem::STATE_UNPROCESSED);
        }

        $em->flush();

        $output->writeln('Finished with result: ' . $result);
    }
}