<?php
// src/Alstef/Ihm/Translation/StkwsizLoad.php
namespace Alstef\IhmBundle\Services;

use Alstef\IhmBundle\Services\DBConnection;
use Alstef\IhmBundle\Services\Logger;

class StkwsizLoad
{
  private $bdd;
  private $logger;
  private $dataStkwsiz;

  public function __construct (DBConnection $bdd, Logger $logger) {
    $this->bdd = $bdd;
    $this->logger = $logger;

    $this->logger->info("Create STKWSIZ");
    $this->dataStkwsiz = array();

    $this->logger->info("Début Load stkwsiz");

    $req = "select OBJET, TYPE, TAILLE, DECIM, TAILLE_AFFICH, EXPR_REGUL, CHAINE_REMPLISSAGE, MASQUE_EN_BDD "
          ." from STKWSIZ";

    foreach ($bdd->getRows($req) as $row) {
      $this->dataStkwsiz[$row['OBJET']] = $row;
    }
  }

  public function getDataStkwsiz($objet) {
    //if (array_keys(print_r($this->dataStkwsiz), $objet).count() > 0) {
      return $this->dataStkwsiz[$objet];
    // }
    // else {
    //   return array();
    // }
  }
}
