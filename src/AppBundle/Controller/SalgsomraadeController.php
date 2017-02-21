<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Salgsomraade;
use AppBundle\Form\SalgsomraadeType;

/**
 * Salgsomraade controller.
 *
 * @Route("/salgsomraade")
 * @Security("has_role('ROLE_ADMIN')")
 */
class SalgsomraadeController extends BaseController {

  public function init(Request $request) {
    parent::init($request);
    $this->breadcrumbs->addItem('salgsomraade.labels.singular', $this->generateUrl('salgsomraade_index'));
  }

  /**
   * Lists all Salgsomraade entities.
   *
   * @Route("/", name="salgsomraade_index")
   * @Method("GET")
   */
  public function indexAction(Request $request) {
    // Get sort, and direction.
    $sort = $request->query->get('sort');
    $direction = $request->query->get('direction');
    $search = $request->query->get('search');
    if (!isset($sort)) {
      $sort = 'id';
    }
    if (!isset($direction)) {
      $direction = 'desc';
    }
    if (!isset($search)) {
      $search = [];
    }

    // Setup query.
    $query = $this->getDoctrine()
      ->getManager()
      ->getRepository('AppBundle:Salgsomraade')
      ->findBy($search, [$sort => $direction]);

    // Apply pagination.
    $paginator = $this->get('knp_paginator');
    $pagination = $paginator->paginate(
      $query,
      $request->query->get('page', 1),
      20
    );


    return $this->render('salgsomraade/index.html.twig', array('pagination' => $pagination));
  }

  /**
   * Creates a new Salgsomraade entity.
   *
   * @Route("/new", name="salgsomraade_new")
   * @Method({"GET", "POST"})
   */
  public function newAction(Request $request) {
    $salgsomraade = new Salgsomraade();
    $form = $this->createForm('AppBundle\Form\SalgsomraadeType', $salgsomraade);
    $form->handleRequest($request);

    $this->breadcrumbs->addItem('common.new', $this->generateUrl('salgsomraade_index'));

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($salgsomraade);
      $em->flush();

      $this->container->get('grundsalg.communication')->saveSalgsomraade($salgsomraade);

      return $this->redirectToRoute('salgsomraade_show', array('id' => $salgsomraade->getId()));
    }

    return $this->render('salgsomraade/new.html.twig', array(
      'salgsomraade' => $salgsomraade,
      'form' => $form->createView(),
    ));
  }

  /**
   * Finds and displays a Salgsomraade entity.
   *
   * @Route("/{id}", name="salgsomraade_show")
   * @Method("GET")
   */
  public function showAction(Salgsomraade $salgsomraade) {
    $this->breadcrumbs->addItem($salgsomraade, $this->generateUrl('salgsomraade_show', array('id' => $salgsomraade->getId())));

    $deleteForm = $this->createDeleteForm($salgsomraade);

    return $this->render('salgsomraade/show.html.twig', array(
      'salgsomraade' => $salgsomraade,
      'delete_form' => $deleteForm->createView(),
    ));
  }

  /**
   * Displays a form to edit an existing Salgsomraade entity.
   *
   * @Route("/{id}/edit", name="salgsomraade_edit")
   * @Method({"GET", "POST"})
   */
  public function editAction(Request $request, Salgsomraade $salgsomraade) {
    $deleteForm = $this->createDeleteForm($salgsomraade);
    $editForm = $this->createForm('AppBundle\Form\SalgsomraadeType', $salgsomraade);
    $editForm->handleRequest($request);

    $this->breadcrumbs->addItem($salgsomraade, $this->generateUrl('salgsomraade_show', array('id' => $salgsomraade->getId())));
    $this->breadcrumbs->addItem('common.edit', $this->generateUrl('salgsomraade_show', array('id' => $salgsomraade->getId())));

    if ($editForm->isSubmitted() && $editForm->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($salgsomraade);
      $em->flush();

      $this->container->get('grundsalg.communication')->saveSalgsomraade($salgsomraade);

      return $this->redirectToRoute('salgsomraade_edit', array('id' => $salgsomraade->getId()));
    }

    return $this->render('salgsomraade/edit.html.twig', array(
      'salgsomraade' => $salgsomraade,
      'edit_form' => $editForm->createView(),
      'delete_form' => $deleteForm->createView(),
    ));
  }

  /**
   * Deletes a Salgsomraade entity.
   *
   * @Route("/{id}", name="salgsomraade_delete")
   * @Method("DELETE")
   */
  public function deleteAction(Request $request, Salgsomraade $salgsomraade) {
    $form = $this->createDeleteForm($salgsomraade);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->remove($salgsomraade);
      $em->flush();
    }

    return $this->redirectToRoute('salgsomraade_index');
  }

  /**
   * Creates a form to delete a Salgsomraade entity.
   *
   * @param Salgsomraade $salgsomraade The Salgsomraade entity
   *
   * @return \Symfony\Component\Form\Form The form
   */
  private function createDeleteForm(Salgsomraade $salgsomraade) {
    return $this->createFormBuilder()
      ->setAction($this->generateUrl('salgsomraade_delete', array('id' => $salgsomraade->getId())))
      ->setMethod('DELETE')
      ->getForm();
  }
}
