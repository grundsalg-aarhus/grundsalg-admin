<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Lokalplan;
use AppBundle\Form\LokalplanType;

/**
 * Lokalplan controller.
 *
 * @Route("/lokalplan")
 * @Security("has_role('ROLE_ADMIN')")
 */
class LokalplanController extends BaseController
{

  public function init(Request $request) {
    parent::init($request);
    $this->breadcrumbs->addItem('lokalplan.labels.singular', $this->generateUrl('lokalplan_index'));
  }

    /**
     * Lists all Lokalplan entities.
     *
     * @Route("/", name="lokalplan_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $sort = $request->query->get('sort');
        $direction = $request->query->get('direction');

        if (!isset($sort)) {
            $sort = 'id';
        }
        if (!isset($direction)) {
            $direction = 'desc';
        }

        $query = $em->getRepository('AppBundle:Lokalplan')->findBy([], [$sort => $direction]);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            20
        );


    return $this->render('lokalplan/index.html.twig', array('pagination' => $pagination));
    }

    /**
     * Creates a new Lokalplan entity.
     *
     * @Route("/new", name="lokalplan_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $lokalplan = new Lokalplan();
        $form = $this->createForm('AppBundle\Form\LokalplanType', $lokalplan);
        $form->handleRequest($request);

        $this->breadcrumbs->addItem('common.new', $this->generateUrl('lokalplan_index'));

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($lokalplan);
            $em->flush();

            return $this->redirectToRoute('lokalplan_show', array('id' => $lokalplan->getId()));
        }

        return $this->render('lokalplan/new.html.twig', array(
            'lokalplan' => $lokalplan,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Lokalplan entity.
     *
     * @Route("/{id}", name="lokalplan_show")
     * @Method("GET")
     */
    public function showAction(Lokalplan $lokalplan)
    {
    $this->breadcrumbs->addItem($lokalplan, $this->generateUrl('lokalplan_show', array('id' => $lokalplan->getId())));

            $deleteForm = $this->createDeleteForm($lokalplan);

        return $this->render('lokalplan/show.html.twig', array(
            'lokalplan' => $lokalplan,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Lokalplan entity.
     *
     * @Route("/{id}/edit", name="lokalplan_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Lokalplan $lokalplan )
    {
        $deleteForm = $this->createDeleteForm($lokalplan);
        $editForm = $this->createForm('AppBundle\Form\LokalplanType', $lokalplan);
        $editForm->handleRequest($request);

        $this->breadcrumbs->addItem($lokalplan, $this->generateUrl('lokalplan_show', array('id' => $lokalplan->getId())));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('lokalplan_show', array('id' => $lokalplan->getId())));

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($lokalplan);
            $em->flush();

            return $this->redirectToRoute('lokalplan_edit', array('id' => $lokalplan->getId()));
        }

        return $this->render('lokalplan/edit.html.twig', array(
            'lokalplan' => $lokalplan,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Lokalplan entity.
     *
     * @Route("/{id}", name="lokalplan_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Lokalplan $lokalplan)
    {
        $form = $this->createDeleteForm($lokalplan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($lokalplan);
            $em->flush();
        }

        return $this->redirectToRoute('lokalplan_index');
    }

    /**
     * Creates a form to delete a Lokalplan entity.
     *
     * @param Lokalplan $lokalplan The Lokalplan entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Lokalplan $lokalplan)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('lokalplan_delete', array('id' => $lokalplan->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
