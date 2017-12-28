<?php

namespace Alstef\IhmBundle\FormEntity\MSGIHM;

class MESSAGE
{
  protected $message ="";
  protected $stkwsiz;
  protected $session;
  protected $pad_string = "~";

  protected $TRMNOM_EMETTEUR;
  protected $USR_NOM_EMETTEUR;

  public function __construct($container) {
    $this->stkwsiz = $container->get('alstef.stkwsiz');
    $this->session = $container->get('session');
  }
  
  public function getTRMNOMEMETTEUR () {
    return $this->TRMNOM_EMETTEUR;
  }
  public function setTRMNOMEMETTEUR ($TRMNOM_EMETTEUR) {
    $this->TRMNOM_EMETTEUR = $TRMNOM_EMETTEUR;
    return $this;
  }

  public function getUSRNOMEMETTEUR () {
    return $this->USR_NOM_EMETTEUR;
  }
  public function setUSRNOMEMETTEUR ($USR_NOM_EMETTEUR) {
    $this->USR_NOM_EMETTEUR = $USR_NOM_EMETTEUR;
    return $this;
  }

  public function add($column, $value)
  {
    $lgColumn = $this->stkwsiz->getDataStkwsiz($column)['TAILLE'];

    $tmp = str_pad ($value, $lgColumn, $this->pad_string);
    $this->message .= $tmp;
    $this->message .= $this->pad_string;
  }

  public function getMessage(){
    return $this->message;
  }
  public function initMessage(){
    $this->message = "";
    return $this;
  }

}
