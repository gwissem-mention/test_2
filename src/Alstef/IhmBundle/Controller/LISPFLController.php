<?php

namespace Alstef\IhmBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Alstef\IhmBundle\Form\LISPFLType;
use Alstef\IhmBundle\FormEntity\MSGIHM\LISPFL;
use Alstef\IhmBundle\FormEntity\MSGIHM\MSGIHM;

class LISPFLController extends Controller
{
  // Sur init de l'écran, et sur chargement des critères 'Sélectionner' ou 'Exclure'
  public function initAction(Request $request) {
    $logger = $this->get('alstef.logger');

    $logger->info('Debut');

    if ($request->getMethod() == 'POST') {      // Initialisation de l'écran
      $idTab = "LISPFL";
      return $this->render('AlstefIhmBundle:Screen:LISPFL.html.twig',
                            array ('idtab' => $idTab));
    }
  }

  // Sur Click du bouton Afficher
  public function afficherAction(Request $request) {
    $logger = $this->get('alstef.logger');
    $bdd = $this->get('alstef.bdd');
    $translator = $this->get('translator');

    $logger->info('Debut');

    $sql = "select PROFIL, PROFIL_LIB,"
    . " (select count(*) from IHMUSR u where u.PROFIL = p.PROFIL and u.VISIBLE = 'OUI') as NB_USR,"
      . " DATCRE as DATCRE_COURTE, USR_NOM, DATMAJ as DATMAJ_COURTE,"
      . " AUTOR_DELETE, AUTOR_MODIF, VISIBLE"
      . " from IHM_PROFIL p";
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

    $LISPFL = new LISPFL($this->get('service_container'));
    if (array_key_exists('row', $parametres)) {
      $LISPFL->setParamFromRow($parametres['row'], 'tmp');
    }
    $form = $this->createForm(LISPFLType::class, $LISPFL,['container' => $this->container]);

    $idTab = "LISPFL";
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

    $LISPFL = new LISPFL($this->get('service_container'));
    $LISPFL->setParamFromRow($parametres['lispfl'], $typmod);
    $LISPFL->setMAJTRMMessage();

    $MSGIHM = new MSGIHM($terminal, 'CFGMNG','MAJTRM' , $LISPFL->getMessage());
    $MSGIHM->setAttCR(false);
    $MSGIHM->envMess($bdd, $logger);

    return new JsonResponse(array("data" => array()));
  }

}
