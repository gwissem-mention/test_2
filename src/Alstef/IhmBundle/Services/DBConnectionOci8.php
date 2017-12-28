<?php
// src/Alstef/IhmBundle/Services/DBConnectionOci8.php

namespace Alstef\IhmBundle\Services;

use Symfony\Component\HttpFoundation\Session\Session;

class DBConnectionOci8 extends DBConnection
{
  // Connexion � la BDD
  // ==================
  public function __construct(Session $session, Logger $logger) {
    $this->session = $session;
    $this->logger = $logger;
    $this->logger->debug("DBConnectionOci8. D�but");

    $bd_host = '127.0.0.1';
    $bd_service = 'XE';
    $bd_user = 'stkpprg';
    $bd_pass = 'stkpprg';

    // connexion �la base Oracle et cr�ation de l'objet
    $this->connexion = oci_pconnect($bd_user, $bd_pass, $bd_host."/".$bd_service);
    if (!$this->connexion) {
      $this->logger->error("Erreur connexion BDD");
    } else {
      $this->logger->debug("Connection ouverte");
    }
    $this->logger->debug("DBConnectionOci8. Fin");
  }

  // execute : Ex�cution d'une requ�te. Retourne le 'statement', ou NULL
  private function execSql ($requete, $params = NULL) {
    $this->logger->debug ("execSql. D�but, requete >$requete< params " . print_r($params, true));
    $stmt = oci_parse ($this->connexion, $requete);
    if ($params != NULL) {
      foreach (array_keys($params) as $key) {
        !oci_bind_by_name ($stmt, $key, $params[$key]);
      }
    }
    $res = oci_execute ($stmt);
    $this->logger->info("oci_execute. retour = $res");
    return $res ? $stmt : NULL;
  }

  // execute : Ex�cution imm�diate d'une requ�te (Insert, Update, Delete)
  public function execute ($requete, $params = NULL) {
    $this->logger->debug("execute. D�but");
    return $this->execSql ($requete, $params) != NULL;
    $this->logger->debug("execute. Fin");
  }

  // getRows : Lecture des enregistrements correspondants � une requ�te donn�e en param�tre
  public function getRows ($requete, $params = NULL) {
    $this->logger->debug("getRows. D�but");
    $stmt = $this->execSql ($requete, $params);
    $rows = array();
    oci_fetch_all ($stmt, $rows, NULL, NULL, OCI_FETCHSTATEMENT_BY_ROW);
    oci_free_statement ($stmt);
    return $rows;
    $this->logger->debug("getRows. Fin");
  }
  
  public function getValeurs($objet) {
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
