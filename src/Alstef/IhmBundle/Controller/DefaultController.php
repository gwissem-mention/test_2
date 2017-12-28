<?php

namespace Alstef\IhmBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
  public function indexAction()
  {
    return $this->render('AlstefIhmBundle:Default:index.html.twig');
  }
}
