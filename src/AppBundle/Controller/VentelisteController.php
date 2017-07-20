<?php

namespace AppBundle\Controller;

use Doctrine\ORM\EntityNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class VentelisteController
 */
class VentelisteController extends Controller {

  /**
   * @param Request $request
   *
   * @Route(path = "/interessent/waitlist", name = "interessent_create")
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function waitlistAction(Request $request) {

    $id = $request->query->get('id');

    $returnPath = $this->generateUrl(
      'easyadmin',
      [
        'action' => 'edit',
        'id' => $id,
        'entity' => $request->query->get('entity'),
        'menuIndex' => $request->query->get('menuIndex'),
        'submenuIndex' => $request->query->get('submenuIndex'),
      ]
    );

    return $this->redirectToRoute(
      'easyadmin',
      [
        'action' => 'new',
        'entity' => 'interessent',
        'referer' => $returnPath,
        'menuIndex' => $request->query->get('menuIndex'),
        'submenuIndex' => $request->query->get('submenuIndex'),
        'grundId' => $id,
      ]
    );
  }

  /**
   * @param Request $request
   *
   * @Route(path = "/interessent/cancel_waitlist", name = "interessent_cancel")
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function cancelWaitlistAction(Request $request) {
    $em = $this->getDoctrine()->getManager();
    $repository = $this->getDoctrine()->getRepository('AppBundle:Interessent');

    $id = $request->query->get('id');
    $referer = $request->query->get('referer');

    $interessent = $repository->find($id);
    $reservationer = $interessent->getReservationer();

    $flashMessage = "flash.waitlist_allready_cancelled";
    $flashType = 'warning';

    foreach ($reservationer as $reservation) {
      if(!$reservation->isAnnulleret()) {
        $flashMessage = "flash.waitlist_cancelled";
        $flashType = 'success';
      }
      $reservation->setAnnulleret(TRUE);
    }

    $translator = $this->get('translator');
    $this->addFlash($flashType, $translator->trans($flashMessage));

    $em->flush();

    $entityEditUrl = $this->generateUrl(
      'easyadmin',
      [
        'action' => 'edit',
        'id' => $id,
        'entity' => $request->query->get('entity'),
      ]
    );

    $url = empty($referer) ? $entityEditUrl : $referer;

    return $this->redirect($url);
  }

/**
 * @param Request $request
 *
 * @Route(path = "/interessent/cancel_sale", name = "sale_cancel")
 *
 * @return \Symfony\Component\HttpFoundation\RedirectResponse
 *
 * @throws \Doctrine\ORM\EntityNotFoundException
 */
  public function cancelSaleAction(Request $request) {

    $id = $request->query->get('id');

    if ($id) {
      $repository = $this->getDoctrine()->getRepository('AppBundle:Grund');
      $grund = $repository->find($id);
    } else {
      throw new EntityNotFoundException('Grund id parameter missing');
    }

    if ($grund) {
      // There must be a byer before sale can be cancelled
      if (empty($grund->getKoeberNavn())) {
        $translator = $this->get('translator');
        $this->addFlash('error', $translator->trans('Byer cannot be empty'));

        return $this->redirectToRoute(
          'easyadmin',
          [
            'action' => 'edit',
            'entity' => $request->query->get('entity'),
            'menuIndex' => $request->query->get('menuIndex'),
            'submenuIndex' => $request->query->get('submenuIndex'),
            'id' => $id,
          ]
        );
      } else {
        $returnPath = $this->generateUrl(
          'easyadmin',
          [
            'action' => 'edit',
            'id' => $id,
            'entity' => $request->query->get('entity'),
            'menuIndex' => $request->query->get('menuIndex'),
            'submenuIndex' => $request->query->get('submenuIndex'),
          ]
        );

        return $this->redirectToRoute(
          'easyadmin',
          [
            'action' => 'new',
            'entity' => 'salgshistorik',
            'referer' => $returnPath,
            'menuIndex' => $request->query->get('menuIndex'),
            'submenuIndex' => $request->query->get('submenuIndex'),
            'grundId' => $id,
          ]
        );
      }
    } else {
      throw new EntityNotFoundException(sprintf('Grund with id: %d not found', $id));
    }
  }
}
