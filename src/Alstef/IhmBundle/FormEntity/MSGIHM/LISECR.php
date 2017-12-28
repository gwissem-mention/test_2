<?php

namespace Alstef\IhmBundle\FormEntity\MSGIHM;

use Alstef\IhmBundle\FormEntity\MSGIHM\MESSAGE;

class LISECR extends MESSAGE
{
  private $TRMNOM;
  private $TRMADR;
  private $TYPMOD;

  private $AUTOR_MODIF = 'OUI';
  private $AUTOR_DELETE = 'OUI';
  private $VISIBLE = 'OUI';

  private $RPOINT_OPE;
  private $CONNECTED;
  private $USR_NOM_CONNECTE;
  private $BVOL_USED;
  private $USR_NOM_LAST_LOGIN;
  private $DATCRE_COURTE;
  private $USR_NOM;
  private $DATMAJ_COURTE;
  private $DATUSE_LONGUE;
  
  public function __construct ($container){
    parent::__construct($container);
  }

  public function getTRMNOM () {
    return $this->TRMNOM;
  }
  public function setTRMNOM ($TRMNOM) {
    $this->TRMNOM = $TRMNOM;
    return $this;
  }

  public function getTRMADR () {
    return $this->TRMADR;
  }
  public function setTRMADR ($TRMADR) {
    $this->TRMADR = $TRMADR;
    return $this;
  }

  public function getTYPMOD () {
    return $this->TYPMOD;
  }
  public function setTYPMOD ($TYPMOD) {
    $this->TYPMOD = $TYPMOD;
    return $this;
  }

  public function getAUTORMODIF () {
    return $this->AUTOR_MODIF;
  }
  public function setAUTORMODIF ($AUTOR_MODIF) {
    $this->AUTOR_MODIF = $AUTOR_MODIF;
    return $this;
  }

  public function getAUTORDELETE () {
    return $this->AUTOR_DELETE;
  }
  public function setAUTORDELETE ($AUTOR_DELETE) {
    $this->AUTOR_DELETE = $AUTOR_DELETE;
    return $this;
  }

  public function getVISIBLE () {
    return $this->VISIBLE;
  }
  public function setVISIBLE ($VISIBLE) {
    $this->VISIBLE = $VISIBLE;
    return $this;
  }

  public function getRPOINTOPE () {
    return $this->RPOINT_OPE;
  }

  public function getCONNECTED () {
    return $this->CONNECTED;
  }

  public function getUSRNOMCONNECTE () {
    return $this->USR_NOM_CONNECTE;
  }

  public function getBVOLUSED () {
    return $this->BVOL_USED;
  }

  public function getUSRNOMLASTLOGIN () {
    return $this->USR_NOM_LAST_LOGIN;
  }

  public function getDATCRECOURTE () {
    return $this->DATCRE_COURTE;
  }

  public function getUSRNOM () {
    return $this->USR_NOM;
  }

  public function getDATMAJCOURTE () {
    return $this->DATMAJ_COURTE;
  }

  public function getDATUSELONGUE () {
    return $this->DATUSE_LONGUE;
  }

  public function setParamFromRow($param, $typmod)
  {
    $this->TRMNOM        =  $param['TRMNOM'];
    $this->TRMADR        =  $param['TRMADR'];
    $this->TYPMOD        =  $typmod;
    $this->AUTOR_MODIF   =  $param['AUTOR_MODIF'];
    $this->AUTOR_DELETE  =  $param['AUTOR_DELETE'];
    $this->VISIBLE       =  $param['VISIBLE'];

    $this->RPOINT_OPE         = $param['RPOINT_OPE'];
    $this->CONNECTED          = $param['CONNECTED'];
    $this->USR_NOM_CONNECTE   = $param['USR_NOM_CONNECTE'];
    $this->BVOL_USED          = $param['BVOL_USED'];
    $this->USR_NOM_LAST_LOGIN = $param['USR_NOM_LAST_LOGIN'];
    $this->DATCRE_COURTE      = $param['DATCRE_COURTE'];
    $this->USR_NOM            = $param['USR_NOM'];
    $this->DATMAJ_COURTE      = $param['DATMAJ_COURTE'];
    $this->DATUSE_LONGUE      = $param['DATUSE_LONGUE'];
  }

  public function setMAJTRMMessage()
  {
    $this->initMESSAGE();
    $this->add('TRMNOM'       , $this->TRMNOM);
    $this->add('TRMADR'       , $this->TRMADR);
    $this->add('TYPMOD'       , $this->TYPMOD);
    $this->add('AUTOR_MODIF'  , $this->AUTOR_MODIF);
    $this->add('AUTOR_DELETE' , $this->AUTOR_DELETE);
    $this->add('VISIBLE'      , $this->VISIBLE);
    $this->add('TRMNOM'       , $this->session->get('terminal'));
    $this->add('USR_NOM'      , $this->session->get('login'));
  }

}
