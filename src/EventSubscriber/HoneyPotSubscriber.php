<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HoneyPotSubscriber implements EventSubscriberInterface
{
    private $honeyPotLogger;

    private $requestStack;

    public function __construct(LoggerInterface $honeyPotLogger, RequestStack $requestStack)
    {
        $this->honeyPotLogger = $honeyPotLogger;
        $this->requestStack = $requestStack;
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SUBMIT => 'checkHoneyJar'
        ];
    }

    public function checkHoneyJar(FormEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request) {
            return;
        }

        $data = $event->getData();

        if (!array_key_exists('landLinePhone', $data) || !array_key_exists('company', $data) || !array_key_exists('subject', $data)) {
            throw new HttpException(400, "Vous essayez de modifier le formulaire, merci de ne pas y toucher!");
        }

        [
            'landLinePhone'     => $landLinePhone,
            'company'           => $company,
            'subject'           => $subject
        ] = $data;

        if ($landLinePhone !== "" && $company !== "" && $subject !== "") {
            $this->honeyPotLogger->info(
                "Une tentative potentielle de spam bot avec l'adresse IP suivante: '{$request->getClientIp()}' a eu lieu. Le champ telephone fixe contenait'{$landLinePhone}' et le champ compagnie contenait '{$company}'."
            );
            throw new HttpException(403, "Partez méchant robot !!");
        }
    }
}
