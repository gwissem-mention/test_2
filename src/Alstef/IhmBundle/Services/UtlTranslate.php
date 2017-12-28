<?php
// src/Alstef/IhmBundle/Services/UtlTranslate.php
namespace Alstef\IhmBundle\Services;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\HttpFoundation\Session\Session;

class UtlTranslate
{
  private $session;
  private $logger;
  private $translator;

  public function __construct (Session $session, Logger $logger, Translator $translator) {
    $this->session = $session;
    $this->logger = $logger;
    $this->translator = $translator;
  }

  public function trad($resource, $locale = "")
  {
    if (empty($locale))
      $locale = $this->session->get("_locale");
    $valeur = $this->translator->trans($resource, array(), 'messages', $locale);
    //$this->logger->info("Traduction de : >".$resource."< en : >".$locale."<. Valeur traduite : >".$valeur."<");

    return $valeur;
  }
}
