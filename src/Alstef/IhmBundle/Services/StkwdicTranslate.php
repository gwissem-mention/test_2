<?php
// src/Alstef/Ihm/Translation/StkwdicTranslate.php
namespace Alstef\IhmBundle\Services;

use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Config\Resource\FileResource;

class StkwdicTranslate implements LoaderInterface
{
  private $bdd;
  private $logger;

  public function __construct (DBConnection $bdd, Logger $logger) {
    $this->bdd = $bdd;
    $this->logger = $logger;
    $this->logger->info("Create STKWDIC");
  }

  public function load($resource, $locale, $domain = 'messages')
  {
    $this->logger->info("Début Load stkwdic");
    $catalogue = new MessageCatalogue($locale);

    $bdd = $this->bdd;
    $req = "select NATURE, OBJET, VALEUR, LIBELLE_COURT, LIBELLE_MOYEN, LIBELLE_LONG"
           ." from STKWDIC"
           ." where LANGUE = :langue";

    $this->logger->info("langue :>".$locale."<");

    foreach ($bdd->getRows($req, array(':langue' => $locale)) as $row) {
      $nature=$row['NATURE'];
      $objet=$row['OBJET'];
      $valeur = $row['VALEUR'];
      $court = $row['LIBELLE_COURT'];
      $moyen = $row['LIBELLE_MOYEN'];
      $long = $row['LIBELLE_LONG'];
      if (empty($valeur))
        $cle = $nature.".".$objet;
      else
        $cle = $nature.".".$objet.".".$valeur;
      $catalogue->set($cle, $moyen);
      $catalogue->set($cle . ".SHORT", $court);
      $catalogue->set($cle . ".LONG", $long);
      // $this->logger->notice("catalogue->set($cle, $moyen)");
    }

    $catalogue->addResource(new FileResource($resource));

    return $catalogue;
  }
}
