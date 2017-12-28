<?php
// src/Alstef/IhmBundle/Entity/Lisvol.php

namespace Alstef\IhmBundle\FormEntity;

class LISBAG
{
  protected $datedebut;
  protected $datefin;
  protected $bsmok;
  protected $bsmdouble;
  protected $bsmdelete;

  public function getDatedebut() {
    return $this->datedebut;
  }
  public function setDatedebut($datedebut) {
    $this->datedebut = $datedebut;
    return $this;
  }

  public function getDatefin() {
    return $this->datefin;
  }
  public function setDatefin($datefin) {
    $this->datefin = $datefin;
    return $this;
  }

  public function getBsmok() {
    return $this->bsmok;
  }
  public function setBsmok($bsmok) {
    $this->bsmok = $bsmok;
    return $this;
  }

  public function getBsmdouble() {
    return $this->bsmdouble;
  }
  public function setBsmdouble($bsmdouble) {
    $this->bsmdouble = $bsmdouble;
    return $this;
  }

  public function getBsmdelete() {
    return $this->bsmdelete;
  }
  public function setBsmdelete($bsmdelete) {
    $this->bsmdelete = $bsmdelete;
    return $this;
  }

}
