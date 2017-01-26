<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\KeywordValue;
use AppBundle\Form\KeywordvalueType;

/**
 * Keywordvalue controller.
 *
 * @Route("/keywordvalue")
 * @Security("has_role('ROLE_ADMIN')")
 */
class KeywordvalueController extends BaseController
{

  public function init(Request $request) {
    parent::init($request);
    $this->breadcrumbs->addItem('keywordvalue.labels.singular', $this->generateUrl('keywordvalue_index'));
  }

    /**
     * Lists all Keywordvalue entities.
     *
     * @Route("/", name="keywordvalue_index")
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
        $query = $this->getDoctrine()->getManager()->getRepository('AppBundle:Keywordvalue')->findBy($search, [$sort => $direction]);

        // Apply pagination.
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            20
        );


    return $this->render('keywordvalue/index.html.twig', array('pagination' => $pagination));
    }

    /**
     * Creates a new Keywordvalue entity.
     *
     * @Route("/new", name="keywordvalue_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $keywordvalue = new KeywordValue();
        $form = $this->createForm('AppBundle\Form\KeywordvalueType', $keywordvalue);
        $form->handleRequest($request);

        $this->breadcrumbs->addItem('common.new', $this->generateUrl('keywordvalue_index'));

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($keywordvalue);
            $em->flush();

            return $this->redirectToRoute('keywordvalue_show', array('id' => $keywordvalue->getId()));
        }

        return $this->render('keywordvalue/new.html.twig', array(
            'keywordvalue' => $keywordvalue,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Keywordvalue entity.
     *
     * @Route("/{id}", name="keywordvalue_show")
     * @Method("GET")
     */
    public function showAction(KeywordValue $keywordvalue)
    {
    $this->breadcrumbs->addItem($keywordvalue, $this->generateUrl('keywordvalue_show', array('id' => $keywordvalue->getId())));

            $deleteForm = $this->createDeleteForm($keywordvalue);

        return $this->render('keywordvalue/show.html.twig', array(
            'keywordvalue' => $keywordvalue,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Keywordvalue entity.
     *
     * @Route("/{id}/edit", name="keywordvalue_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, KeywordValue $keywordvalue )
    {
        $deleteForm = $this->createDeleteForm($keywordvalue);
        $editForm = $this->createForm('AppBundle\Form\KeywordvalueType', $keywordvalue);
        $editForm->handleRequest($request);

        $this->breadcrumbs->addItem($keywordvalue, $this->generateUrl('keywordvalue_show', array('id' => $keywordvalue->getId())));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('keywordvalue_show', array('id' => $keywordvalue->getId())));

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($keywordvalue);
            $em->flush();

            return $this->redirectToRoute('keywordvalue_edit', array('id' => $keywordvalue->getId()));
        }

        return $this->render('keywordvalue/edit.html.twig', array(
            'keywordvalue' => $keywordvalue,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Keywordvalue entity.
     *
     * @Route("/{id}", name="keywordvalue_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, KeywordValue $keywordvalue)
    {
        $form = $this->createDeleteForm($keywordvalue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($keywordvalue);
            $em->flush();
        }

        return $this->redirectToRoute('keywordvalue_index');
    }

    /**
     * Creates a form to delete a Keywordvalue entity.
     *
     * @param KeywordValue $keywordvalue The Keywordvalue entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(KeywordValue $keywordvalue)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('keywordvalue_delete', array('id' => $keywordvalue->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
