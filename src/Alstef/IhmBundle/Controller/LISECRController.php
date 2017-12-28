<?php

namespace Alstef\IhmBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Alstef\IhmBundle\Form\LISECRType;
use Alstef\IhmBundle\FormEntity\MSGIHM\LISECR;
use Alstef\IhmBundle\FormEntity\MSGIHM\MSGIHM;

class LISECRController extends Controller
{
  // Sur init de l'écran, et sur chargement des critères 'Sélectionner' ou 'Exclure'
  public function initAction(Request $request) {
    $logger = $this->get('alstef.logger');

    $logger->info('Debut');

    if ($request->getMethod() == 'POST') {      // Initialisation de l'écran
      $idTab = "LISECR";
      return $this->render('AlstefIhmBundle:Screen:LISECR.html.twig',
                            array ('idtab' => $idTab));
    }
  }

  // Sur Click du bouton Afficher
  public function afficherAction(Request $request) {
    $logger = $this->get('alstef.logger');
    $bdd = $this->get('alstef.bdd');
    $translator = $this->get('translator');

    $logger->info('Debut');

    $sql = "select t.TRMNOM, t.TRMADR, t.RPOINT rpoint_ope,"
    		  . " decode(c1.CNT,null,'---','OUI') CONNECTED, c1.USR_NOM USR_NOM_CONNECTE,"
    		  . " decode(c2.CNT,null,'---','OUI') BVOL_USED,"
  		    . " t.USR_NOM_LAST_LOGIN,"
    		  . " t.DATCRE DATCRE_COURTE, t.USR_NOM, t.DATMAJ DATMAJ_COURTE, t.DATUSE DATUSE_LONGUE,"
    		  . " t.AUTOR_MODIF, t.AUTOR_DELETE, t.VISIBLE"
    		  . " from IHMTRM t,"
        	. " (select TRMNOM, USR_NOM, count(*) CNT from DIAUSR d where d.DIA_CODE='MENU' group by USR_NOM, TRMNOM) c1,"
        	. " (select TRMNOM, count(*) CNT from DIAUSR d where d.DIA_CODE in ('PLANIF','PLAANN') group by TRMNOM) c2"
    		  . " where t.TRMNOM=c1.TRMNOM (+) and t.TRMNOM=c2.TRMNOM (+)";
    $logger->info('req >' . $sql . '<');

    $tab = [];
    $findData = false;
    foreach ($bdd->getRows($sql) as $row) {
      $tab[] = $row;
      $findData = true;
    }

    $indexArray = array();
    if ($findData) {
      $listcolumn = array_keys($tab[0]);
      foreach ($listcolumn as $value) {
        $column = array("data" => $value, "title" => $translator->trans('LIBELLE.'.$value.'.SHORT'), "id" => $value);
        array_push($indexArray, $column);
      }
    } else {
      $indexArray = array(array("data" => "NO_DATA", "title" => $translator->trans('LIBELLE.NO_DATA')));
      array_push($tab, array(array("NO_DATA" => "")));
    }

    $ret = array("data" => $tab, "columns" => $indexArray);
    $resp = new JsonResponse($ret);
    // $logger->info('JsonResponse = >' . $resp . '<');
    return $resp;
  }

  // Create
  public function getpopupAction(Request $request) {
    $logger = $this->get('alstef.logger');
    $logger->info('Debut');
    $parametres = $request->request->all();
    $logger->info("request->request".print_r($parametres, TRUE));

    $LISECR = new LISECR($this->get('service_container'));
    if (array_key_exists('row', $parametres)) {
      $LISECR->setParamFromRow($parametres['row'], 'tmp');
    }
    $form = $this->createForm(LISECRType::class, $LISECR,['container' => $this->container]);

    $idTab = "LISECR";
    $typmod = "";
    if (array_key_exists('typmod', $parametres)) {
      $typmod = $parametres['typmod'];
    }
    $buttonType = $parametres['buttonType'];
    $editable = $parametres['editable'];
    $withConfirmation = $parametres['withConfirmation'];
    return $this->render('AlstefIhmBundle:Form:Popup.html.twig',
                          array ( 'idtab' => $idTab,
                                  'buttonType' => $buttonType,
                                  'typmod' => $typmod,
                                  'editable' => $editable,
                                  'withConfirmation' => $withConfirmation,
                                  'form' => $form->createView()));
  }
  // Create
  public function actionAction(Request $request) {
    $logger = $this->get('alstef.logger');
    $bdd = $this->get('alstef.bdd');

    $logger->info('Debut.');
    $parametres = $request->request->all();
    $session = $request->getSession()->all();
    // $logger->info("parametres".print_r($parametres, TRUE));
    // $logger->info("parametres".print_r($session, TRUE));
    $typmod = $parametres['typmod'];
    $terminal = $session['terminal'];

    $LISECR = new LISECR($this->get('service_container'));
    $LISECR->setParamFromRow($parametres['lisecr'], $typmod);
    $LISECR->setMAJTRMMessage();

    $MSGIHM = new MSGIHM($terminal, 'CFGMNG','MAJTRM' , $LISECR->getMessage());
    $MSGIHM->setAttCR(false);
    $MSGIHM->envMess($bdd, $logger);

    return new JsonResponse(array("data" => array()));
  }

}
