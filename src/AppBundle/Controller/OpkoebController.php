<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Opkoeb;
use AppBundle\Form\OpkoebType;

/**
 * Opkoeb controller.
 *
 * @Route("/opkoeb")
 * @Security("has_role('ROLE_ADMIN')")
 */
class OpkoebController extends BaseController
{

  public function init(Request $request) {
    parent::init($request);
    $this->breadcrumbs->addItem('opkoeb.labels.singular', $this->generateUrl('opkoeb_index'));
  }

    /**
     * Lists all Opkoeb entities.
     *
     * @Route("/", name="opkoeb_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $sort = $request->query->get('sort');
        $direction = $request->query->get('direction');

        $query = $em->getRepository('AppBundle:Opkoeb')->findBy([], [$sort => $direction]);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            20
        );


    return $this->render('opkoeb/index.html.twig', array('pagination' => $pagination));
        }

    /**
     * Creates a new Opkoeb entity.
     *
     * @Route("/new", name="opkoeb_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $opkoeb = new Opkoeb();
        $form = $this->createForm('AppBundle\Form\OpkoebType', $opkoeb);
        $form->handleRequest($request);

        $this->breadcrumbs->addItem('common.new', $this->generateUrl('opkoeb_index'));

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($opkoeb);
            $em->flush();

            return $this->redirectToRoute('opkoeb_show', array('id' => $opkoeb->getId()));
        }

        return $this->render('opkoeb/new.html.twig', array(
            'opkoeb' => $opkoeb,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Opkoeb entity.
     *
     * @Route("/{id}", name="opkoeb_show")
     * @Method("GET")
     */
    public function showAction(Opkoeb $opkoeb)
    {
    $this->breadcrumbs->addItem($opkoeb, $this->generateUrl('opkoeb_show', array('id' => $opkoeb->getId())));

            $deleteForm = $this->createDeleteForm($opkoeb);

        return $this->render('opkoeb/show.html.twig', array(
            'opkoeb' => $opkoeb,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Opkoeb entity.
     *
     * @Route("/{id}/edit", name="opkoeb_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Opkoeb $opkoeb )
    {
        $deleteForm = $this->createDeleteForm($opkoeb);
        $editForm = $this->createForm('AppBundle\Form\OpkoebType', $opkoeb);
        $editForm->handleRequest($request);

        $this->breadcrumbs->addItem($opkoeb, $this->generateUrl('opkoeb_show', array('id' => $opkoeb->getId())));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('opkoeb_show', array('id' => $opkoeb->getId())));

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($opkoeb);
            $em->flush();

            return $this->redirectToRoute('opkoeb_edit', array('id' => $opkoeb->getId()));
        }

        return $this->render('opkoeb/edit.html.twig', array(
            'opkoeb' => $opkoeb,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Opkoeb entity.
     *
     * @Route("/{id}", name="opkoeb_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Opkoeb $opkoeb)
    {
        $form = $this->createDeleteForm($opkoeb);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($opkoeb);
            $em->flush();
        }

        return $this->redirectToRoute('opkoeb_index');
    }

    /**
     * Creates a form to delete a Opkoeb entity.
     *
     * @param Opkoeb $opkoeb The Opkoeb entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Opkoeb $opkoeb)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('opkoeb_delete', array('id' => $opkoeb->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
