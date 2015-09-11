<?php

namespace MusicBundle\Email;

class Mailer
{
    private $swiftMailer;

    private $mailerUser;

    private $mailerName;

    private $twig;

    public function __construct(\Swift_Mailer $swiftMailer, $mailerUser, $mailerName, \Twig_Environment $twig)
    {
        $this->swiftMailer = $swiftMailer;
        $this->mailerUser = $mailerUser;
        $this->mailerName = $mailerName;
        $this->twig = $twig;
    }

    public function send($to, $tplFile, array $tplVars = [])
    {
        $tpl = $this->twig->loadTemplate($tplFile);

        $message = \Swift_Message::newInstance()
            ->setSubject($tpl->renderBlock('subject', $tplVars))
            ->setFrom($this->mailerUser, $this->mailerName)
            ->setTo($to)
            ->setBody($tpl->renderBlock('body_html', $tplVars), 'text/html')
        ;

        if ($this->swiftMailer->send($message) < 1) {
            throw new \RuntimeException('Unable to deliver email');
        }
    }
}