<?php

namespace Alstef\IhmBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Alstef\IhmBundle\FormEntity\LISBAG;
use Alstef\IhmBundle\Form\LISBAGType;

class LISBAGController extends Controller
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
      $idTab = "LISBAG";
      // Creation des champs du formulaire Login
      $form = $this->createForm(LISBAGType::class, new LISBAG(),['container' => $this->container]);
      return $this->render('AlstefIhmBundle:Screen:LISBAG.html.twig', array ('form' => $form->createView(),'idtab' => $idTab));
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
    $lisbag = array();
    if ($request->getMethod() == 'POST') {
      $parametres = $request->request;
    } else {
      $parametres = $request->query;
    }

    foreach ($parametres->keys() as $key) {
      $lisbag[$key] = $parametres->get($key);
    }

    $logger->info("parametres " . print_r($lisbag, TRUE));

      $sql_ok = "SELECT to_char(BAG.datcre, 'YYYY-MM-DD HH24:MI:SS.FF3') datcre_bsm_stamp, BAG.tag, "
      ."BAG.tag_bsm, BAG.nompax_bsm, BAG.charg_autor_bsm, BAG.volref_oaci, BAG.volref_aff, BAG.exception_bsm,"
      ."BAG.rpoint, BAG.sdd_vol, BAG.esc_bsm, BAG.dest_volsuiv_bsm, BAG.classe_bsm, BAG.segregation, BAG.pnr_bsm,"
      ." BAG.siege_bsm, BAG.bpm_uld, BAG.bpm_airc_compart, BAG.bpm_bag_irreg, BAG.poids_mesure,  BAG.STASEC stasec_red,"
      ." BAG.STARAD starad_red, BAG.STAITEM staitem_red, BAG.STAGOOD stagood_red,'OK' as STATAG, BAG.volref_suiv_aff,"
      ." BAG.sdd_volsuiv, BAG.volref_prec_aff, BAG.sdd_volprec, BAG.id_vol FROM bagage BAG WHERE 1=1 and "
      . "(datcre >= to_date(:date_debut, 'YYYY/MM/DD-HH24:MI:SS') and "
      . " datcre < to_date(:date_fin, 'YYYY/MM/DD-HH24:MI:SS' )+1/86400)";

      $sql_dbl = "SELECT to_char(BAG.datcre, 'YYYY-MM-DD HH24:MI:SS.FF3') datcre_bsm_stamp, BAG.tag,"
      ." BAG.tag_bsm, BAG.nompax_bsm, BAG.charg_autor_bsm, BAG.volref_oaci, BAG.volref_aff, BAG.exception_bsm,"
      ." BAG.rpoint, BAG.sdd_vol, BAG.esc_bsm, BAG.dest_volsuiv_bsm, BAG.classe_bsm, BAG.segregation, BAG.pnr_bsm,"
      ." BAG.siege_bsm, BAG.bpm_uld, BAG.bpm_airc_compart, BAG.bpm_bag_irreg, BAG.poids_mesure,  BAG.STASEC stasec_red,"
      ." BAG.STARAD starad_red, BAG.STAITEM staitem_red, BAG.STAGOOD stagood_red,'DBL' as STATAG, BAG.volref_suiv_aff,"
      ." BAG.sdd_volsuiv, BAG.volref_prec_aff, BAG.sdd_volprec, BAG.id_vol FROM bsm_doublon BAG WHERE 1=1 and "
      . "(datcre >= to_date(:date_debut, 'YYYY/MM/DD-HH24:MI:SS') and "
      . " datcre < to_date(:date_fin, 'YYYY/MM/DD-HH24:MI:SS' )+1/86400)";

      $sql_del = "SELECT to_char(BAG.datcre, 'YYYY-MM-DD HH24:MI:SS.FF3') datcre_bsm_stamp, BAG.tag,"
      ." BAG.tag_bsm, BAG.nompax_bsm, BAG.charg_autor_bsm, BAG.volref_oaci, BAG.volref_aff, BAG.exception_bsm,"
      ." BAG.rpoint, BAG.sdd_vol, BAG.esc_bsm, BAG.dest_volsuiv_bsm, BAG.classe_bsm, BAG.segregation, BAG.pnr_bsm,"
      ." BAG.siege_bsm, BAG.bpm_uld, BAG.bpm_airc_compart, BAG.bpm_bag_irreg, BAG.poids_mesure,  BAG.STASEC stasec_red,"
      ." BAG.STARAD starad_red, BAG.STAITEM staitem_red, BAG.STAGOOD stagood_red,'DEL' as STATAG, BAG.volref_suiv_aff,"
      ." BAG.sdd_volsuiv, BAG.volref_prec_aff, BAG.sdd_volprec, BAG.id_vol FROM bsm_delete BAG WHERE 1=1 and "
      . "(datcre >= to_date(:date_debut, 'YYYY/MM/DD-HH24:MI:SS') and "
      . " datcre < to_date(:date_fin, 'YYYY/MM/DD-HH24:MI:SS' )+1/86400)";

    if (intval($lisbag['lisbag']['bsm_ok']) == 1)
    {
      $sql =  $sql_ok;
    }
    if (intval($lisbag['lisbag']['bsm_double']) == 1)
    {
      if ($sql != "")
      {
        $sql .= " UNION ";
      }
      $sql .= $sql_dbl;
    }
    if (intval($lisbag['lisbag']['bsm_delete']) == 1)
    {
      if ($sql != "")
      {
        $sql .= " UNION ";
      }
      $sql .=  $sql_del;
    }
    $sql .= " order by datcre_bsm_stamp desc";

    $params = array(':date_debut' => $lisbag['lisbag']['date_debut'],
                    ':date_fin' => $lisbag['lisbag']['date_fin']);

    $logger->info('req >' . $sql . '<');
    $logger->info('params ' . print_r($params, TRUE));
    $tab = array();
    $findData = "false";
    foreach ($bdd->getRows($sql, $params) as $row) {
      $tab[] = $row;
      $findData = "true";
    }

    $indexArray = array();
    if ($findData == "true")
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
