<?php

namespace Alstef\IhmBundle\FormEntity\MSGIHM;

class MSGIHM
{
  protected $trmnomEmetteur;
  protected $tacheDestinatrice;
  protected $mesfct;
  protected $message;
  protected $priority;
  protected $attCR = false;

  public function __construct ($trmnomEmetteur, $tacheDestinatrice, $mesfct, $message) {
    $this->trmnomEmetteur = $trmnomEmetteur;
    $this->tacheDestinatrice = $tacheDestinatrice;
    $this->mesfct = $mesfct;
    $this->message = $message;
    $this->priority = '0';
  }

  public function setPriority($priority){
    $this->priority = $priority;
    return $this;
  }

  public function getAttCR(){
    return $attCR;
  }

  public function setAttCR($attCR){
    $this->attCR = $attCR;
    return $this;
  }

  public function envMess($bdd, $logger){
      $sqlseq = "SELECT seqmsgecr.nextval SEQ from dual";
      $msgSeq = $bdd->getRows($sqlseq)[0]['SEQ'];
      $logger->info("Emiss. Msg >" . $this->message . "<");

      $requete = "INSERT INTO msgecr"
        . " (trmnom_emetteur, nomlog_recepteur, message, fonction, cr, status, priorite, datmsg, seq_msgecr, attcrp)"
        . " VALUES ('" . $this->trmnomEmetteur . "', '" . $this->tacheDestinatrice . "', '" . $this->message . "', '" . $this->mesfct . "', '00', 'A', "
        . $this->priority . ", SYSDATE, " . $msgSeq . ", " . ($this->attCR?"1":"0") . ")";

      $bdd->execute($requete);

      if ($this->attCR)
      {
        // BHE 11/01/2013 ajout lecture status
        $requete = "SELECT cr, cr_corps, status"
        		. " from msgecr"
        		. " WHERE trmnom_emetteur ='" . $this->trmnomEmetteur . "' AND seq_msgecr='" . $msgSeq . "' AND status = 'T'";

        $nbEnreg = 0;
        do {
          $rows = $bdd->getRows($requete);
          $nbEnreg = count($rows);
          if ($nbEnreg > 0)
          {
            $row = $rows[0];
            $msgCr = $row['CR'];
            $corpsMsgCr = $row['CR_CORPS'];
            $logger->info("CR=" . $msgCr . ", corps=" . $corpsMsgCr . " fct=" . $this->mesfct . "<");
            break;
            // if ($msgCr == "00")
            // {
            //
            // }
            // else
            // {
            //
            // }
          }
          else
          {
            sleep(1);
          }

        } while ($nbEnreg == 0);

      }
    }
}
