<?php

namespace Alstef\IhmBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Alstef\IhmBundle\FormEntity\LISVOL;
use Alstef\IhmBundle\Form\LISVOLType;

class LISVOLController extends Controller
{
  private function valeurs_critere ($critere) {
    $logger = $this->get('alstef.logger');
    $bdd = $this->get('alstef.bdd');
    $sql = "select distinct VALEUR, LIBELLE_MOYEN from STKWDIC where NATURE = 'VALEUR' and OBJET = :critere and LANGUE = :langue order by VALEUR";
    $langue = $this->get('session')->get('_locale');
    $tab = array();
    foreach ($bdd->getRows($sql, array(':critere' => $critere, ':langue' => $langue)) as $row) {
      $tab[$row['VALEUR']] = utf8_encode($row['LIBELLE_MOYEN']);
    }
    $logger->info('retour requete Ajax. requete >' . $sql . '<, critere >' . $critere . '<, langue >' . $langue . '< : ' . print_r ($tab, TRUE));
    return $tab;
  }

  // Sur init de l'�cran, et sur chargement des crit�res 'S�lectionner' ou 'Exclure'
  public function initAction(Request $request) {
    $logger = $this->get('alstef.logger');

      $logger->info('Debut. Appel Ajax = ' . ($request->isXmlHttpRequest() ? "True" : "False") . ', method = ' . $request->getMethod());

    if ($request->getMethod() == 'POST') {      // Initialisation de l'�cran
      $idTab = "LISVOL";
      // Creation des champs du formulaire Login
      $form = $this->createForm(LISVOLType::class, new LISVOL(),['container' => $this->container]);
      return $this->render('AlstefIhmBundle:Screen:LISVOL.html.twig', array ('form' => $form->createView(),'idtab' => $idTab));
    }
  }

  // Sur premier affichage du tableau des vols
  public function afficherVideAction(Request $request) {
    $logger = $this->get('alstef.logger');
    $logger->info('Debut. Appel Ajax = ' . ($request->isXmlHttpRequest() ? "True" : "False") . ', method = ' . $request->getMethod());
    return new JsonResponse(array("data" => array()));
  }

  // Sur Click du bouton Afficher
  public function afficherAction(Request $request) {
    $logger = $this->get('alstef.logger');
    $bdd = $this->get('alstef.bdd');
    $translator = $this->get('translator');

    $logger->info('Debut. Appel Ajax = ' . ($request->isXmlHttpRequest() ? "True" : "False") . ', method = ' . $request->getMethod());

    $logger->info('lecture parametres');
    $lisvol = array();
    if ($request->getMethod() == 'POST') {
      $parametres = $request->request;
    } else {
      $parametres = $request->query;
    }

    foreach ($parametres->keys() as $key) {
      $lisvol[$key] = $parametres->get($key);
    }

    $logger->info("parametres " . print_r($lisvol, TRUE));

    $sql =  "select VV.D_DEBTRI_VOL D_DEBTRI_VOL_HHMI, VV.MVTNUM, VV.ID_FIMS,"
          . "VV.IATA_CIE, VV.LIG, VV.VOLREF_AFF, VV.VOLREF_OACI,"
          . "(SELECT count(*) FROM VUE_VOL_SHARE VS WHERE vs.id_vol = VV.id_vol) as nb_vol_com, "
	        . "VV.SDD_VOL, VV.STD_VOL, VV.ETD_VOL, VV.EVOL, VV.DST_VOL,"
          . " VV.HIGH_RISK, VV.HIGH_RISK HIGH_RISK_RED, VV.CATEGORIE_VOL CATEGORIE_VOL_RED, VV.TYPE_VOL TYPE_VOL_RED,"
          . " VV.ESC1||'.'||VV.ESC2||'.'||VV.ESC3||'.'||VV.ESC4 ESCALES,"
          . " TERMINAL, PARKING, TYPE_AVION, "
          . " NVL(VA.ANO_VOL, '') ANO_VOL,"
          . " NVL(VA.FCMODE, '') FCMODE,"
          . " TO_CHAR(VV.D_DEBTRI_VOL, 'HH24:MI') D_DEBTRI_VOL, VV.ORGDEM,"
          . " TO_CHAR(VV.D_FINTRI_VOL, 'HH24:MI') D_FINTRI_VOL,"
	        . " ( SELECT count(*) FROM VUE_CHUTE VU WHERE vu.id_vol = VV.id_vol) as nb_chutes, VV.id_vol"
          . " from VUE_VOL VV, VUE_VOL_ANOMALIE VA"
	        . " WHERE VV.id_vol = VA.id_vol (+)"
	        . " and (vv.sdd_vol like :sdd_vol or :sdd_vol is null) ";

          if (intval($lisvol['lisvol']['vol_du_jour']) == 1) {
            $sql .= "AND VV.SDD_VOL = TO_CHAR(sysdate,'YYYY/MM/DD')";
          }
          if (intval($lisvol['lisvol']['vol_affecte']) == 1 and intval($lisvol['lisvol']['vol_nonaffecte']) == 0) {
            $sql .= " AND (SELECT count(*) from VUE_CHUTE VU where VV.id_vol = vu.id_vol) > 0";
          }
          if (intval($lisvol['lisvol']['vol_nonaffecte']) == 0 and intval($lisvol['lisvol']['vol_affecte']) == 0) {
            $sql .= " AND (SELECT count(*) from VUE_CHUTE VU where VV.id_vol = vu.id_vol) = 0";
          }

          if (intval($lisvol['lisvol']['en_warning']) == 1 and intval($lisvol['lisvol']['en_erreur']) == 1) {
            $sql .= " AND VA.fcmode IS NOT NULL AND VA.fcmode in ('WRN','ERR')";
          }
          else if (intval($lisvol['lisvol']['en_erreur']) == 1) {
            $sql .= " AND VA.fcmode IS NOT NULL AND VA.fcmode = 'ERR'";
          }
          else if (intval($lisvol['lisvol']['en_warning']) == 1) {
            $sql .= " AND VA.fcmode IS NOT NULL AND VA.fcmode = 'ERR'";
          }


    $params = array(':sdd_vol' => $lisvol['lisvol']['sdd_vol']);

    $logger->info('req >' . $sql . '<');
    $logger->info('params ' . print_r($params, TRUE));
    $tab = array();
    $findData = false;
    foreach ($bdd->getRows($sql, $params) as $row) {
      $tab[] = $row;
      $findData = true;
    }

    $indexArray = array();
    if ($findData)
    {
      $listcolumn = array_keys($tab[0]);
      foreach ($listcolumn as $key => $value) {
        $column = array("data" => $value, "title" => $translator->trans('LIBELLE.'.$value.'.SHORT'));
        array_push($indexArray, $column);
      }
    }
    else
    {
      $indexArray = array(array("data" => "NO_DATA", "title" => $translator->trans('LIBELLE.NO_DATA')));
      array_push($tab, array(array("NO_DATA" => "")));
    }

    $ret = array("data" => $tab, "columns" => $indexArray);
    $resp = new JsonResponse($ret);
    // $logger->info('JsonResponse = >' . $resp . '<');
    return $resp;
  }
}
