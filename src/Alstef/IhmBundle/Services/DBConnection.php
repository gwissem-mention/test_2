<?php
// src/Alstef/IhmBundle/Services/DBConnection.php

namespace Alstef\IhmBundle\Services;

abstract class DBConnection
{
  protected $connexion;
  protected $logger;
  protected $session;

  // execute : Exécution immédiate d'une requête (Insert, Update, Delete)
  public abstract function execute ($requete, $params = NULL);

  // getRows : Lecture des enregistrements correspondants à une requête donnée en paramètre
  public abstract function getRows ($requete, $params = NULL);

  // getValeurs : Lecture des enregistrements de STKWDIC de type VALEUR, pour la locale courante et pour l'objet passé en paramêtre
  public abstract function getValeurs ($objet);
}
