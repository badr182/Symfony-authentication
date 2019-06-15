<?php

namespace App\Services;

use Twig\Environment ;

class MailerServices {
    
    private $mailer ;
    private $twig ; 

    public function __construct(\Swift_Mailer $mailer, $twig)
    {
        $this->mailer = $mailer ;
        $this->twig = $twig ;
    }

    // send message 
    public function send($name)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('send@example.com')
            ->setTo('recipient@example.com')
            ->setBody(
                $this->twig->render(
                    // templates/emails/registration.html.twig
                    'emails/registration.html.twig',
                    ['name' => $name]
                ),
                'text/html'
            )
        /*
         * If you also want to include a plaintext version of the message
        ->addPart(
            $this->renderView(
                'emails/registration.txt.twig',
                ['name' => $name]
            ),
            'text/plain'
        )
        */
        ;

        $this->mailer->send($message);
    }

}