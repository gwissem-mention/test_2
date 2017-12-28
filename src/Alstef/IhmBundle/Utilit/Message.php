<?php
// src/Alstef/IhmBundle/Utilit/Message.php

namespace Alstef\IhmBundle\Utilit;

class Message
{
  const CR_TIMEOUT = "-1";        // Compte-rendu en cas de time-out. TODO : quelle valeur ?

  private $bdd;
  private $logger;
  private $msg;
  private $mesfct;
  private $timeout;
  private $session;

  public function __construct ($mesfct) {
    global $kernel;
    $container = $kernel->getContainer();
    $this->bdd = $container->get('alstef.bdd');
    $this->logger = $container->get('alstef.logger');
    $this->session = $container->get('session');
    $this->msg = "";
    $this->mesfct = $mesfct;
  }

  public function add($objet, $valeur) {
    $rows = $this->bdd->getRows('select TAILLE from STKWSIZ where OBJET = :objet', array(':objet' => $objet));
    if (count($rows) != 1) {
      $this->logger->emergency("Taille objet '" . $objet . "' inconnue");
    } else {
      $this->msg = sprintf ("%s%-".$rows[0]['TAILLE']."s~", $this->msg, $valeur);
      $this->logger->debug("objet >" . $objet . "<, taille >" . $rows[0]['TAILLE'] . "<, valeur >" . $valeur . "<, msg >" . $this->msg . "<");
    }
    return $this;
  }

  public function sendWaitResp($recepteur, $timeout) {
    $this->logger->info("Début. récepteur >" . $recepteur . "< timeout " . $timeout);
    $rows = $this->bdd->getRows('select SEQMSGECR.nextval NEXTVAL from DUAL');
    $seq_msgecr = $rows[0]['NEXTVAL'];
    $req = "insert into MSGECR (TRMNOM_EMETTEUR, NOMLOG_RECEPTEUR, MESSAGE, FONCTION, STATUS, PRIORITE, DATMSG, SEQ_MSGECR, ATTCRP)"
           . " values (:trmnom, :recepteur, :message, :fonction, :status, :priorite, sysdate, :seq_msgecr, :attcrp)";
    $params = array (':trmnom' => $this->session->get('terminal'),
                     ':recepteur' => $recepteur,
                     ':message' => $this->msg,
                     ':fonction' => $this->mesfct,
                     ':status' => 'C',
                     ':priorite' => 10,
                     ':seq_msgecr' => $seq_msgecr,
                     ':attcrp' => 1);
    $res = $this->bdd->execute ($req, $params);
    if (!$res) {
      $this->logger->emergency('Erreur insertion dans MSMGECR');
    }
    $this->logger->info("Message inséré. seq_msgecr = " . $seq_msgecr);

    // Attente jusqu'au Time-out, et renvoi du compte-rendu, ou d'un cr signifiant time-out
    // TODO : sous Linux, utiliser les fonction pcntl
    $cr = "";
    for ($i = 0; $i < $timeout && $cr == ""; $i++) {
      $rows = $this->bdd->getRows('select CR from MSGECR where SEQ_MSGECR = :seq_msgecr', array(':seq_msgecr' => $seq_msgecr));
      $cr = $rows[0]['CR'];
      $this->logger->info("CR = >" . $cr . "<");
      if ($cr != '') {
        return $cr;
      } else {
        sleep(1);
      }
    }
    return self::CR_TIMEOUT;
  }
}
