<?php
// src/Alstef/IhmBundle/EventListener/LoggedInUserListener.php
namespace Alstef\IhmBundle\Services;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Router;

class LoggedInUserListener
{
  private $logger;
  private $router;

  public function __construct(Logger $logger, Router $router) {
    $this->logger = $logger;
    $this->router = $router;
  }

  public function onKernelRequest(GetResponseEvent $event) {
    // On teste si la requête est bien la requête principale
    if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
      return;
    }

    // Routes à ignorer : _profiler, _wdt, ...
    $routeName = $event->getRequest()->get('_route');
    if (preg_match ('/(_(profiler|wdt)|css|images|js)/', $routeName)) {
      return;
    }

    // Si on n'a pas de variable 'login' ou 'terminal' en session, et la route demandée n'est pas la page d'accueil, redirection
    $session = $event->getRequest()->getSession();
    if ($routeName !== "Ihm_homepage" && $routeName !== "Ihm_login") {
      foreach (array('login', 'terminal') as $variable) {
        if (!$session->has($variable)) {
          $this->logger->warning("Route demandée >" . $routeName . "< et " . $variable . " non enregistré => Redirection vers page d'accueil");
          break;
        }
      }
    }
  }
}
