<?php
// src/Alstef/IhmBundle/Services/DBConnectionPdo.php

namespace Alstef\IhmBundle\Services;

use Symfony\Component\HttpFoundation\Session\Session;

class DBALConnection extends DBConnection
{
  // Connexion � la BDD
  // ==================
  public function __construct(Session $session, Logger $logger, \Doctrine\DBAL\Connection $connexion) {
    $this->session = $session;
    $this->logger = $logger;
    $this->connexion = $connexion;
  }

  // execSql : Ex�cution imm�diate d'une requ�te. Appel� par execute et getRows
  private function execSql ($requete, $params = array()) {
    $stmt = $this->connexion->prepare ($requete);

    foreach ($params as $key => $value) {
      if (!strncmp($key, ":", 1)) {
        $bind=substr($key, 1);
        $stmt->bindValue ($bind, $value);
      }
      else {
        $this->logger->warning("Bind parameter".$key." without ':' in requete >".$requete."<");
        return array();
      }
    }

    $res = $stmt->execute ();
  //  $this->logger->info("execSql. retour = $res");
    return $res ? $stmt : NULL;
  }

  // execute : Ex�cution imm�diate d'une requ�te (Insert, Update, Delete)
  public function execute ($requete, $params = array()) {
    return $this->execSql($requete, $params);
  }

  // getRows : Lecture des enregistrements correspondants � une requ�te donn�e en param�tre
  public function getRows ($requete, $params = array()) {
    $stmt = $this->execSql($requete, $params);
    return $stmt->fetchAll ();
  }

  // getValeurs : Lecture des enregistrements de STKWDIC de type VALEUR, pour la locale courante et pour l'objet pass� en param�tre
  public function getValeurs ($objet) {
    $req = "select VALEUR, LIBELLE_MOYEN from STKWDIC"
           ." where LANGUE = :langue"
           ." and NATURE = 'VALEUR'"
           ." and OBJET = :objet"
           ." order by VALEUR asc";
    $valeurs = array();
    foreach ($this->getRows($req, array (':langue' => $this->session->get('_locale'), ':objet' => $objet)) as $row) {
      $valeurs[$row['VALEUR']] = $row['LIBELLE_MOYEN'];
    }
    $this->logger->debug("Objet >". $objet . "<, valeurs = " . print_r($valeurs, TRUE));
    return $valeurs;
  }
}
