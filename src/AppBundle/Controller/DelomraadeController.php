<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Delomraade;
use AppBundle\Form\DelomraadeType;

/**
 * Delomraade controller.
 *
 * @Route("/delomraade")
 * @Security("has_role('ROLE_ADMIN')")
 */
class DelomraadeController extends BaseController
{

  public function init(Request $request) {
    parent::init($request);
    $this->breadcrumbs->addItem('delomraade.labels.singular', $this->generateUrl('delomraade_index'));
  }

    /**
     * Lists all Delomraade entities.
     *
     * @Route("/", name="delomraade_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
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
        $query = $this->getDoctrine()->getManager()->getRepository('AppBundle:Delomraade')->findBy($search, [$sort => $direction]);

        // Apply pagination.
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            20
        );


    return $this->render('delomraade/index.html.twig', array('pagination' => $pagination));
    }

    /**
     * Creates a new Delomraade entity.
     *
     * @Route("/new", name="delomraade_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $delomraade = new Delomraade();
        $form = $this->createForm('AppBundle\Form\DelomraadeType', $delomraade);
        $form->handleRequest($request);

        $this->breadcrumbs->addItem('common.new', $this->generateUrl('delomraade_index'));

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($delomraade);
            $em->flush();

            return $this->redirectToRoute('delomraade_show', array('id' => $delomraade->getId()));
        }

        return $this->render('delomraade/new.html.twig', array(
            'delomraade' => $delomraade,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Delomraade entity.
     *
     * @Route("/{id}", name="delomraade_show")
     * @Method("GET")
     */
    public function showAction(Delomraade $delomraade)
    {
    $this->breadcrumbs->addItem($delomraade, $this->generateUrl('delomraade_show', array('id' => $delomraade->getId())));

            $deleteForm = $this->createDeleteForm($delomraade);

        return $this->render('delomraade/show.html.twig', array(
            'delomraade' => $delomraade,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Delomraade entity.
     *
     * @Route("/{id}/edit", name="delomraade_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Delomraade $delomraade )
    {
        $deleteForm = $this->createDeleteForm($delomraade);
        $editForm = $this->createForm('AppBundle\Form\DelomraadeType', $delomraade);
        $editForm->handleRequest($request);

        $this->breadcrumbs->addItem($delomraade, $this->generateUrl('delomraade_show', array('id' => $delomraade->getId())));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('delomraade_show', array('id' => $delomraade->getId())));

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($delomraade);
            $em->flush();

            return $this->redirectToRoute('delomraade_edit', array('id' => $delomraade->getId()));
        }

        return $this->render('delomraade/edit.html.twig', array(
            'delomraade' => $delomraade,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Delomraade entity.
     *
     * @Route("/{id}", name="delomraade_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Delomraade $delomraade)
    {
        $form = $this->createDeleteForm($delomraade);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($delomraade);
            $em->flush();
        }

        return $this->redirectToRoute('delomraade_index');
    }

    /**
     * Creates a form to delete a Delomraade entity.
     *
     * @param Delomraade $delomraade The Delomraade entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Delomraade $delomraade)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('delomraade_delete', array('id' => $delomraade->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
