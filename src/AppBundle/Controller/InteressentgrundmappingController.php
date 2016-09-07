<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Interessentgrundmapping;
use AppBundle\Form\InteressentgrundmappingType;

/**
 * Interessentgrundmapping controller.
 *
 * @Route("/interessentgrundmapping")
 * @Security("has_role('ROLE_ADMIN')")
 */
class InteressentgrundmappingController extends BaseController
{

  public function init(Request $request) {
    parent::init($request);
    $this->breadcrumbs->addItem('interessentgrundmapping.labels.singular', $this->generateUrl('interessentgrundmapping_index'));
  }

    /**
     * Lists all Interessentgrundmapping entities.
     *
     * @Route("/", name="interessentgrundmapping_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $sort = $request->query->get('sort');
        $direction = $request->query->get('direction');

        $query = $em->getRepository('AppBundle:Interessentgrundmapping')->findBy([], [$sort => $direction]);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            20
        );


    return $this->render('interessentgrundmapping/index.html.twig', array('pagination' => $pagination));
        }

    /**
     * Creates a new Interessentgrundmapping entity.
     *
     * @Route("/new", name="interessentgrundmapping_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $interessentgrundmapping = new Interessentgrundmapping();
        $form = $this->createForm('AppBundle\Form\InteressentgrundmappingType', $interessentgrundmapping);
        $form->handleRequest($request);

        $this->breadcrumbs->addItem('common.new', $this->generateUrl('interessentgrundmapping_index'));

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($interessentgrundmapping);
            $em->flush();

            return $this->redirectToRoute('interessentgrundmapping_show', array('id' => $interessentgrundmapping->getId()));
        }

        return $this->render('interessentgrundmapping/new.html.twig', array(
            'interessentgrundmapping' => $interessentgrundmapping,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Interessentgrundmapping entity.
     *
     * @Route("/{id}", name="interessentgrundmapping_show")
     * @Method("GET")
     */
    public function showAction(Interessentgrundmapping $interessentgrundmapping)
    {
    $this->breadcrumbs->addItem($interessentgrundmapping, $this->generateUrl('interessentgrundmapping_show', array('id' => $interessentgrundmapping->getId())));

            $deleteForm = $this->createDeleteForm($interessentgrundmapping);

        return $this->render('interessentgrundmapping/show.html.twig', array(
            'interessentgrundmapping' => $interessentgrundmapping,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Interessentgrundmapping entity.
     *
     * @Route("/{id}/edit", name="interessentgrundmapping_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Interessentgrundmapping $interessentgrundmapping )
    {
        $deleteForm = $this->createDeleteForm($interessentgrundmapping);
        $editForm = $this->createForm('AppBundle\Form\InteressentgrundmappingType', $interessentgrundmapping);
        $editForm->handleRequest($request);

        $this->breadcrumbs->addItem($interessentgrundmapping, $this->generateUrl('interessentgrundmapping_show', array('id' => $interessentgrundmapping->getId())));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('interessentgrundmapping_show', array('id' => $interessentgrundmapping->getId())));

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($interessentgrundmapping);
            $em->flush();

            return $this->redirectToRoute('interessentgrundmapping_edit', array('id' => $interessentgrundmapping->getId()));
        }

        return $this->render('interessentgrundmapping/edit.html.twig', array(
            'interessentgrundmapping' => $interessentgrundmapping,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Interessentgrundmapping entity.
     *
     * @Route("/{id}", name="interessentgrundmapping_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Interessentgrundmapping $interessentgrundmapping)
    {
        $form = $this->createDeleteForm($interessentgrundmapping);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($interessentgrundmapping);
            $em->flush();
        }

        return $this->redirectToRoute('interessentgrundmapping_index');
    }

    /**
     * Creates a form to delete a Interessentgrundmapping entity.
     *
     * @param Interessentgrundmapping $interessentgrundmapping The Interessentgrundmapping entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Interessentgrundmapping $interessentgrundmapping)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('interessentgrundmapping_delete', array('id' => $interessentgrundmapping->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
