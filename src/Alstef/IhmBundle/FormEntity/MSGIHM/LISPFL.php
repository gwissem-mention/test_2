<?php

namespace Alstef\IhmBundle\FormEntity\MSGIHM;

use Alstef\IhmBundle\FormEntity\MSGIHM\MESSAGE;

class LISPFL extends MESSAGE
{
  private $typmod;
  private $profil;
  private $profilLib;
  private $nbUsr;
  private $datcre;
  private $datmaj;
  private $trmnom;
  private $usrNom;

  private $autorModif = 'OUI';
  private $autorDelete = 'OUI';
  private $visible = 'OUI';
  
  public function __construct ($container){
    parent::__construct($container);
  }

  public function getTypmod () {
    return $this->Typmod;
  }
  public function setTypmod ($Typmod) {
    $this->Typmod = $Typmod;
    return $this;
  }

  public function getProfil () {
    return $this->profil;
  }
  public function setProfil ($profil) {
    $this->profil = $profil;
    return $this;
  }

  public function getProfilLib () {
    return $this->profilLib;
  }
  public function setProfilLib ($profilLib) {
    $this->profilLib = $profilLib;
    return $this;
  }

  public function getAutorModif () {
    return $this->autorModif;
  }
  public function setAutorModif ($autorModif) {
    $this->autorModif = $autorModif;
    return $this;
  }

  public function getAutorDelete () {
    return $this->autorDelete;
  }
  public function setAutorDelete ($autorDelete) {
    $this->autorDelete = $autorDelete;
    return $this;
  }

  public function getVisible () {
    return $this->visible;
  }
  public function setVisible ($visible) {
    $this->visible = $visible;
    return $this;
  }
  
  public function getNbUsr () {
    return $this->nbUsr;
  }
  
  public function getDatcreCourte() {
    return $this->datcre;
  }
  
  public function getDatmajCourte() {
    return $this->datmaj;
  }
  
  public function getUsrNom() {
    return $this->usrNom;
  }
  
  public function setParamFromRow($param, $typmod)
  {
    $this->typmod = $typmod;
    $this->profil = $param['PROFIL'];
    $this->profilLib = $param['PROFIL_LIB'];
    $this->autorModif = $param['AUTOR_MODIF'];
    $this->autorDelete = $param['AUTOR_DELETE'];
    $this->visible = $param['VISIBLE'];
    $this->nbUsr = $param['NB_USR'];
    $this->datcre = $param['DATCRE_COURTE'];
    $this->datmaj = $param['DATMAJ_COURTE'];
    $this->usrNom = $param['USR_NOM'];
  }

  public function setMAJPFLMessage()
  {
    $this->initMESSAGE();
    $this->add('TYPMOD', $this->typmod);
    $this->add('PROFIL', $this->profil);
    $this->add('PROFIL_LIB', $this->profilLib);
    $this->add('AUTOR_MODIF', $this->autorModif);
    $this->add('AUTOR_DELETE', $this->autorDelete);
    $this->add('VISIBLE', $this->visible);
    $this->add('TRMNOM', $this->session->get('terminal'));
    $this->add('USR_NOM', $this->session->get('login'));
  }
}
