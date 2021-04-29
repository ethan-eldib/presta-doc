<?php

namespace App\Service;

use Twig\Environment;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\bridge\Twig\Mime\WrappedTemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Address;

class EmailService extends AbstractController
{
    /** 
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    public function sendEmail(
        $toEmail,
        $replyTo = null,
        $templateEmailTwig,
        $subject,
        array $variablesToPassToViewTwig = []
    ) {
        $email = (new TemplatedEmail())
            ->from('contact@presta-doc.fr')
            ->to(new Address($toEmail))
            ->subject($subject)
            ->replyTo($replyTo)
            ->htmlTemplate($templateEmailTwig)
            ->context([
                'vars' => $variablesToPassToViewTwig
            ]);
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            throw $e;
        }
    }
}
