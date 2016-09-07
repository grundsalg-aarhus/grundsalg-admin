<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Interessent;
use AppBundle\Form\InteressentType;

/**
 * Interessent controller.
 *
 * @Route("/interessent")
 * @Security("has_role('ROLE_ADMIN')")
 */
class InteressentController extends BaseController
{

  public function init(Request $request) {
    parent::init($request);
    $this->breadcrumbs->addItem('interessent.labels.singular', $this->generateUrl('interessent_index'));
  }

    /**
     * Lists all Interessent entities.
     *
     * @Route("/", name="interessent_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $sort = $request->query->get('sort');
        $direction = $request->query->get('direction');

        $query = $em->getRepository('AppBundle:Interessent')->findBy([], [$sort => $direction]);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            20
        );


    return $this->render('interessent/index.html.twig', array('pagination' => $pagination));
        }

    /**
     * Creates a new Interessent entity.
     *
     * @Route("/new", name="interessent_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $interessent = new Interessent();
        $form = $this->createForm('AppBundle\Form\InteressentType', $interessent);
        $form->handleRequest($request);

        $this->breadcrumbs->addItem('common.new', $this->generateUrl('interessent_index'));

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($interessent);
            $em->flush();

            return $this->redirectToRoute('interessent_show', array('id' => $interessent->getId()));
        }

        return $this->render('interessent/new.html.twig', array(
            'interessent' => $interessent,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Interessent entity.
     *
     * @Route("/{id}", name="interessent_show")
     * @Method("GET")
     */
    public function showAction(Interessent $interessent)
    {
    $this->breadcrumbs->addItem($interessent, $this->generateUrl('interessent_show', array('id' => $interessent->getId())));

            $deleteForm = $this->createDeleteForm($interessent);

        return $this->render('interessent/show.html.twig', array(
            'interessent' => $interessent,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Interessent entity.
     *
     * @Route("/{id}/edit", name="interessent_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Interessent $interessent )
    {
        $deleteForm = $this->createDeleteForm($interessent);
        $editForm = $this->createForm('AppBundle\Form\InteressentType', $interessent);
        $editForm->handleRequest($request);

        $this->breadcrumbs->addItem($interessent, $this->generateUrl('interessent_show', array('id' => $interessent->getId())));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('interessent_show', array('id' => $interessent->getId())));

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($interessent);
            $em->flush();

            return $this->redirectToRoute('interessent_edit', array('id' => $interessent->getId()));
        }

        return $this->render('interessent/edit.html.twig', array(
            'interessent' => $interessent,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Interessent entity.
     *
     * @Route("/{id}", name="interessent_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Interessent $interessent)
    {
        $form = $this->createDeleteForm($interessent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($interessent);
            $em->flush();
        }

        return $this->redirectToRoute('interessent_index');
    }

    /**
     * Creates a form to delete a Interessent entity.
     *
     * @param Interessent $interessent The Interessent entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Interessent $interessent)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('interessent_delete', array('id' => $interessent->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
