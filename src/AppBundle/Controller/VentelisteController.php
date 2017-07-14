<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Grund;
use AppBundle\Entity\Interessent;
use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class VentelisteController extends Controller {

  /**
   * @Route(path = "/interessent/waitlist", name = "interessent_create")
   */
  public function waitlistAction(Request $request) {

    $id = $request->query->get('id');

    $returnPath = $this->generateUrl('easyadmin', array(
      'action' => 'edit',
      'id' => $id,
      'entity' => $request->query->get('entity'),
      'menuIndex' => $request->query->get('menuIndex'),
      'submenuIndex' => $request->query->get('submenuIndex'),
    ));

    return $this->redirectToRoute('easyadmin', array(
      'action' => 'new',
      'entity' => 'interessent',
      'referer' => $returnPath,
      'menuIndex' => $request->query->get('menuIndex'),
      'submenuIndex' => $request->query->get('submenuIndex'),
      'grundId' => $id,
    ));

  }

}
