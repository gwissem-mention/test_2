<?php
// src/Alstef/IhmBundle/EventListener/LocaleListener.php
namespace Alstef\IhmBundle\Services;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LocaleListener implements EventSubscriberInterface
{
  private $logger;
  private $defaultLocale;

  public function __construct(Logger $logger, $defaultLocale = 'en') {
    $this->logger = $logger;
    $this->defaultLocale = $defaultLocale;
  }

  public function onKernelRequest(GetResponseEvent $event) {
    $request = $event->getRequest();
    //$this->logger->info("D�but. hasSession >" . $request->hasSession() . "< hasPreviousSession >" . $request->hasPreviousSession() . "< Requested URI >" . $request->getRequestUri() . "<");
    // Si pas encore de session, rien � faire
    if (!$request->hasSession()) {
      return;
    }

    // On teste si la requ�te est bien la requ�te principale
    if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
      return;
    }

    // on regarde si la locale a �t� fix� dans le param�tre de routing _locale
    if ($locale = $request->attributes->get('_locale')) {
    // ou dans un param�tre de la requ�te
    } elseif ($locale = $request->get('_locale')) {
    }

    if ($locale) {
      // Traitement demande de changement de locale : on v�rifie que c'est une locale connue
      $locales = $request->getSession()->get('locales');
      if (!isset($locales[$locale])) {
        $locale = "";
      }
    }

    if ($locale) {
      $request->setLocale($locale);
    } else {
      // si aucune locale n'a �� fix�e explicitement dans la requ�te ou est invalide, on utilise celle de la session
      $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
    }
    $request->getSession()->set('_locale', $request->getLocale());
  }

  public static function getSubscribedEvents() {
    return array(
      // doit �tre enregistr� avant le Locale listener par d�faut
      KernelEvents::REQUEST => array(array('onKernelRequest', 17)),
    );
  }
}
