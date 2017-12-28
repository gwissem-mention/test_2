<?php
// src/Alstef/IhmBundle/Entity/Lisvol.php

namespace Alstef\IhmBundle\FormEntity;

class LISVOL
{
  protected $sdd_vol;
  protected $vol_du_jour;
  protected $vol_affecte;
  protected $vol_nonaffecte;
  protected $en_warning;
  protected $en_erreur;

  public function getSddVol() {
    return $this->sdd_vol;
  }

  public function getVolDuJour() {
    return $this->vol_du_jour;
  }

  public function getVolAffecte() {
    return $this->vol_affecte;
  }

  public function getVolNonaffecte() {
    return $this->vol_nonaffecte;
  }

  public function getEnWarning() {
    return $this->en_warning;
  }

  public function getEnErreur() {
    return $this->en_erreur;
  }

}
