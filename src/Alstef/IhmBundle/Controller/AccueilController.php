<?php

namespace Alstef\IhmBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Alstef\IhmBundle\FormEntity\Login;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AccueilController extends Controller
{
  public function initAction(Request $request) {
    $session = $request->getSession();
    $logger = $this->get('alstef.logger');
    $bdd = $this->get('alstef.bdd');
    $langues_dispo=array();

    $logger->info("Début");

    // Si la variable de session 'locales' n'existe pas, on lit les locales disponibles, et on met ça en session
    if (!$session->get('locales')) {
      $logger->info("cas 'locales' pas en session");
      // Récupération des langues disponibles
      $sql = "select distinct VALEUR, LIBELLE_MOYEN from STKWDIC where NATURE = 'VALEUR' and OBJET = 'LANGUE' and VALEUR = LANGUE";
      foreach ($bdd->getRows($sql) as $row) {
        $langues_dispo[$row['LIBELLE_MOYEN']] = $row['VALEUR'];
      }
      $request->getSession()->set('locales', $langues_dispo);
      $logger->info("locales en session : " . print_r ($langues_dispo, TRUE));
    }
    $logger->info("locale en session : " . $session->get('_locale'));

    // Si la variable de session 'terminal' n'existe pas, on lit le terminal, et on le met en session
    if (!$session->get('terminal')) {
        $trmadr = gethostbyaddr($request->getClientIp());
      if (!filter_var($trmadr, FILTER_VALIDATE_IP)) {
        $trmadr = explode('.', $trmadr, 2)[0];
      }

      $logger->Info("cas 'terminal' pas en session. trmadr = " . $trmadr);
      $rows = $bdd->getRows('select TRMNOM from IHMTRM where lower(TRMADR) = lower(:trmadr)', array(':trmadr' => $trmadr));
      if (count($rows) > 0) {
        $logger->info('mise en session : terminal => ' . $rows[0]['TRMNOM']);
        $session->set('terminal', $rows[0]['TRMNOM']);
      }
    }

    // Si Terminal non autorisé, on redirige vers un écran 'Terminal non autorisé'
    if (!$session->get('terminal')) {
      return $this->render('AlstefIhmBundle:Screen:non_autorise.html.twig');
    }
    
    // On recherche la dernière locale utilisée sur ce terminal, et on la met en session
    $rows = $bdd->getRows('select LANGUE from IHMTRM where TRMNOM = :terminal', array(':terminal' => $session->get('terminal')));
    if (($locale = $rows[0]['LANGUE']) === null) {
      $locale = "en";
    }
    $logger->info("Mise en session de locale = " . $locale);
    $session->set('_locale', $locale);

    return $this->redirect($this->generateUrl('alstef_ihm_login'));
  }

  public function loginAction(Request $request) {
    $session = $request->getSession();
    $logger = $this->get('alstef.logger');
    $bdd = $this->get('alstef.bdd');
    $trad = $this->get('alstef.traduction');

    $logger->info('Debut. request.locale = >' . $request->getLocale() . '<, session.locale = >' . $session->get('_locale') . '<');
    $logger->info('Ajax : ' . ($request->isXmlHttpRequest()?"true":"false") . ", method : " . $request->getMethod());

    // Création des champs du formulaire Login
    $login = new Login();
    $login->setLanguage($session->get('_locale'));

    $form = $this->createFormBuilder($login)
                 ->add('language', ChoiceType::class, array ('choices' => $session->get('locales'),'choice_translation_domain' => false))
                 ->add('name', TextType::class)
                 ->add('password', PasswordType::class)
                 ->getForm();

    if ($request->isXmlHttpRequest()) {
      // Appelé par Ajax pour changement de Langue
      $tab = array();
      $tab['language'] = $trad->trad('LIBELLE.LANGUE', $request->get('_locale'));
      $tab['name'] = $trad->trad('LIBELLE.USR_NOM', $request->get('_locale'));
      $tab['password'] = $trad->trad('LIBELLE.USR_PSW', $request->get('_locale'));
      $tab['submit']= $trad->trad('BOUTON.BOX_LOGIN', $request->get('_locale'));
      $resp = new JsonResponse($tab);
      $logger->info('JsonResponse = >' . $resp . '<');
      return $resp;
    } else if ($request->getMethod() != 'POST') {
      // Appelé par redirect()
      return $this->render('AlstefIhmBundle:Screen:login.html.twig', array(
        'form' => $form->createView()
      ));
    } else {
      // Appelé sur validation du formulaire
      $form->handleRequest($request);

      $login = $form->get('name')->getData();
      $passwd = $form->get('password')->getData();
      $locale = $form->get('language')->getData();
      $logger->info("login:>".$login."< psw:>".$passwd."< lang:>".$locale."<");

      $rows = $bdd->getRows('select USR_PSW from IHMUSR where USR_NOM = :login', array(':login' => $login));
      if (count($rows) > 0 && $rows[0]['USR_PSW'] == $passwd) {
        $session->set('login', $login);
        $session->set('_locale', $locale);
        $bdd->execute("update IHMTRM set LANGUE = :langue where TRMNOM = :trmnom", array(':langue' => $locale, ':trmnom' => $session->get('terminal')));
        $logger->info("Sauvegarde de la locale '" . $locale . "' pour terminal '" . $session->get('terminal') . "'");
        return $this->redirect($this->generateUrl('alstef_ihm_menu'));
      } else {
        $error = new formerror("Login incorrect");
        $form->addError($error);
        return $this->render('AlstefIhmBundle:Screen:login.html.twig', array(
          'form' => $form->createView()
        ));
      }
    }
  }

  public function disconnectAction(Request $request) {
    $session = $request->getSession();
    $locale = $session->get('_locale');
    $session->invalidate();
    $session->set('_locale', $locale);
    return $this->redirect($this->generateUrl('alstef_ihm_homepage'));
  }

  public function menuAction(Request $request) {
    $session = $request->getSession();
    $logger = $this->get('alstef.logger');
    $logger->info('Debut. request.locale = ' . $request->getLocale() . ', session.locale = ' . $session->get('_locale'));
    $menus = array (
      "GESVOL" => array ("LISVOL"),
      "GESBAG" => array ("LISBAG"),
      "ADMIN" => array ("LISPFL", "LISECR")
    );

    $menus_affiches = array();
    foreach ($menus as $key => $value) {
      $lib_menu = $this->get('translator')->trans('MENU.'.$key);
      $menus_affiches[$key] = array ($lib_menu);
      $sous_menus = $value;
      foreach ($sous_menus as $entree) {
        if ($entree != "0") {
          $lib = $this->get('translator')->trans('DIALOG.'.$entree);
          $menus_affiches[$key][$entree] = $lib;
        } else {
          $menus_affiches[$key][] = 0;
        }
      }
    }

    // $logger->info(print_r($menus_affiches, TRUE));
    return $this->render('AlstefIhmBundle:Screen:Menu.html.twig', array (
      'menus' => $menus_affiches
    ));
  }

  // Rafraichissement des boutons du bandeau
  public function statusRefreshAction(Request $request) {
    $logger = $this->get('alstef.logger');

    $sql = "select nvl(NB_HISERR_ERR, 0) as NB_HISERR_ERR, nvl(NB_HISERR_ERX, 0) as NB_HISERR_ERX,"
				 . " nvl(NB_HISERR_WEX, 0) as NB_HISERR_WEX, nvl(NB_VOL_OK,0) as NB_VOL_OK,"
				 . " nvl(NB_MVT_ERR, 0) as NB_MVT_ERR, nvl(NB_MVT_WRN, 0) as NB_MVT_WRN, nvl(NB_MVT_OK, 0) as NB_MVT_OK,"
				 . " nvl(NB_LNK_ERR, 0) as NB_LNK_ERR, nvl(NB_LNK_WRN, 0) as NB_LNK_WRN, nvl(NB_LNK_OK, 0) as NB_LNK_OK,"
				 . " nvl(NB_EQT_ERR, 0) as NB_EQT_ERR, nvl(NB_EQT_WRN, 0) as NB_EQT_WRN, nvl(NB_EQT_OK, 0) as NB_EQT_OK,"
				 . " nvl(NB_VOL_ERR, 0) as NB_VOL_ERR, nvl(NB_VOL_WRN, 0) as NB_VOL_WRN, nvl(NB_VOL_OK, 0) as NB_VOL_OK,"
				 . " nvl(NB_HRD_ERR, 0) as NB_HRD_ERR, nvl(NB_HRD_WRN, 0) as NB_HRD_WRN, nvl(NB_HRD_OK, 0) as NB_HRD_OK,"
				 . " nvl(NB_ALR_ERR, 0) as NB_ALR_ERR, nvl(NB_ALR_WRN, 0) as NB_ALR_WRN, nvl(NB_ALR_OK, 0) as NB_ALR_OK,"
				 . " nvl(NB_STO_ERR, 0) as NB_STO_ERR, nvl(NB_STO_WRN, 0) as NB_STO_WRN, nvl(NB_STO_OK, 0) as NB_STO_OK,"
				 . " nvl(NB_MOD_ERR, 0) as NB_MOD_ERR, nvl(NB_MOD_WRN, 0) as NB_MOD_WRN, nvl(NB_MOD_OK, 0) as NB_MOD_OK,"
				 . " NBPROC_ARRETE, NBPROC_TROP, ETAAPP, SERVER,"
				 . " to_char (sysdate, 'YYYY-MM-DD HH24:MI:SS') as DATSYS"
				 . " from IHM_VOYANT";
    $row = $this->get('alstef.bdd')->getRows($sql)[0];
    $tab = [];
    $tab['errorButton'] = $this->setButton ($row, 'NB_HISERR_ERR', '', '0', 'LIBELLE.CNTERR');
    $tab['errexButton'] = $this->setButton ($row, 'NB_HISERR_ERX', '', '0', 'LIBELLE.CNTERX');
    $tab['errwarnButton'] = $this->setButton($row, 'NB_HISERR_WEX', '', '0', 'LIBELLE.CNTERW');
    $tab['modesButton'] = $this->setButton($row, 'NB_MOD_ERR', 'NB_MOD_WRN', $row['NB_MOD_OK'], 'LIBELLE.CLOCK_ETAMOD');
    $tab['stockButton'] = $this->setButton($row, 'NB_STO_ERR', 'NB_STO_WRN', $row['NB_STO_OK'], 'LIBELLE.CLOCK_ETASTO');
    $tab['alarmButton'] = $this->setButton($row, 'NB_ALR_ERR', 'NB_ALR_WRN', $row['NB_ALR_OK'], 'LIBELLE.CLOCK_ETAALR');
    $tab['processButton'] = $this->setButton($row, 'NBPROC_ARRETE', 'NBPROC_TROP', 'OK', 'LIBELLE.CLOCK_NBPROC');
    $tab['materielButton'] = $this->setButton($row, 'NB_HRD_ERR', 'NB_HRD_WRN', $row['NB_HRD_OK'], 'LIBELLE.CLOCK_HARD');
    if ($row['NB_HRD_ERR'] == 0 && $row['NB_HRD_WRN'] == 0 && $row['NB_HRD_OK'] == 0)
      $tab['materielButton'] = array("state" => "undef", "value" => $this->get('alstef.traduction')->trad('LIBELLE.CLOCK_HARD') . " 000");
    $tab['volsButton'] = $this->setButton($row, 'NB_VOL_ERR', 'NB_VOL_WRN', $row['NB_VOL_OK'], 'LIBELLE.CLOCK_NBVOLS');
    $tab['mvtsButton'] = $this->setButton($row, 'NB_MVT_ERR', 'NB_MVT_WRN', $row['NB_MVT_OK'], 'LIBELLE.CLOCK_NBMVTS');
    $tab['linksButton'] = $this->setButton($row, 'NB_LNK_ERR', 'NB_LNK_WRN', $row['NB_LNK_OK'], 'LIBELLE.CLOCK_ETACOM');
    $tab['eqptButton'] = $this->setButton($row, 'NB_EQT_ERR', 'NB_EQT_WRN', $row['NB_EQT_OK'], 'LIBELLE.CLOCK_ETAEQT');
    $resp = new JsonResponse($tab);
    //$logger->info('JsonResponse = >' . $resp . '<');
    return $resp;
  }
  
  private function setButton($row, $fieldErr, $fieldWrn, $nbOk, $libDic) {
    $lib = $this->get('alstef.traduction')->trad($libDic);
    if ($row[$fieldErr] > 0)
      $res = array("state" => "err", "value" => $lib . ' ' . $row[$fieldErr]);
    else if (!empty($fieldWrn) && $row[$fieldWrn] > 0)
      $res = array("state" => "warn", "value" => $lib . ' ' . $row[$fieldWrn]);
    else
      $res = array("state" => "ok", "value" => $lib . ' ' . $nbOk);
    return $res;
  }
}
