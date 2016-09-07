<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Postby;
use AppBundle\Form\PostbyType;

/**
 * Postby controller.
 *
 * @Route("/postby")
 * @Security("has_role('ROLE_ADMIN')")
 */
class PostbyController extends BaseController
{

  public function init(Request $request) {
    parent::init($request);
    $this->breadcrumbs->addItem('postby.labels.singular', $this->generateUrl('postby_index'));
  }

    /**
     * Lists all Postby entities.
     *
     * @Route("/", name="postby_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $sort = $request->query->get('sort');
        $direction = $request->query->get('direction');

        $query = $em->getRepository('AppBundle:Postby')->findBy([], [$sort => $direction]);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            20
        );


    return $this->render('postby/index.html.twig', array('pagination' => $pagination));
        }

    /**
     * Creates a new Postby entity.
     *
     * @Route("/new", name="postby_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $postby = new Postby();
        $form = $this->createForm('AppBundle\Form\PostbyType', $postby);
        $form->handleRequest($request);

        $this->breadcrumbs->addItem('common.new', $this->generateUrl('postby_index'));

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($postby);
            $em->flush();

            return $this->redirectToRoute('postby_show', array('id' => $postby->getId()));
        }

        return $this->render('postby/new.html.twig', array(
            'postby' => $postby,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Postby entity.
     *
     * @Route("/{id}", name="postby_show")
     * @Method("GET")
     */
    public function showAction(Postby $postby)
    {
    $this->breadcrumbs->addItem($postby, $this->generateUrl('postby_show', array('id' => $postby->getId())));

            $deleteForm = $this->createDeleteForm($postby);

        return $this->render('postby/show.html.twig', array(
            'postby' => $postby,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Postby entity.
     *
     * @Route("/{id}/edit", name="postby_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Postby $postby )
    {
        $deleteForm = $this->createDeleteForm($postby);
        $editForm = $this->createForm('AppBundle\Form\PostbyType', $postby);
        $editForm->handleRequest($request);

        $this->breadcrumbs->addItem($postby, $this->generateUrl('postby_show', array('id' => $postby->getId())));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('postby_show', array('id' => $postby->getId())));

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($postby);
            $em->flush();

            return $this->redirectToRoute('postby_edit', array('id' => $postby->getId()));
        }

        return $this->render('postby/edit.html.twig', array(
            'postby' => $postby,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Postby entity.
     *
     * @Route("/{id}", name="postby_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Postby $postby)
    {
        $form = $this->createDeleteForm($postby);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($postby);
            $em->flush();
        }

        return $this->redirectToRoute('postby_index');
    }

    /**
     * Creates a form to delete a Postby entity.
     *
     * @param Postby $postby The Postby entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Postby $postby)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('postby_delete', array('id' => $postby->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
