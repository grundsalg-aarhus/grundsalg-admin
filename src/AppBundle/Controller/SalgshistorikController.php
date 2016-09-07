<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Salgshistorik;
use AppBundle\Form\SalgshistorikType;

/**
 * Salgshistorik controller.
 *
 * @Route("/salgshistorik")
 * @Security("has_role('ROLE_ADMIN')")
 */
class SalgshistorikController extends BaseController
{

  public function init(Request $request) {
    parent::init($request);
    $this->breadcrumbs->addItem('salgshistorik.labels.singular', $this->generateUrl('salgshistorik_index'));
  }

    /**
     * Lists all Salgshistorik entities.
     *
     * @Route("/", name="salgshistorik_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $sort = $request->query->get('sort');
        $direction = $request->query->get('direction');

        $query = $em->getRepository('AppBundle:Salgshistorik')->findBy([], [$sort => $direction]);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            20
        );


    return $this->render('salgshistorik/index.html.twig', array('pagination' => $pagination));
        }

    /**
     * Creates a new Salgshistorik entity.
     *
     * @Route("/new", name="salgshistorik_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $salgshistorik = new Salgshistorik();
        $form = $this->createForm('AppBundle\Form\SalgshistorikType', $salgshistorik);
        $form->handleRequest($request);

        $this->breadcrumbs->addItem('common.new', $this->generateUrl('salgshistorik_index'));

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($salgshistorik);
            $em->flush();

            return $this->redirectToRoute('salgshistorik_show', array('id' => $salgshistorik->getId()));
        }

        return $this->render('salgshistorik/new.html.twig', array(
            'salgshistorik' => $salgshistorik,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Salgshistorik entity.
     *
     * @Route("/{id}", name="salgshistorik_show")
     * @Method("GET")
     */
    public function showAction(Salgshistorik $salgshistorik)
    {
    $this->breadcrumbs->addItem($salgshistorik, $this->generateUrl('salgshistorik_show', array('id' => $salgshistorik->getId())));

            $deleteForm = $this->createDeleteForm($salgshistorik);

        return $this->render('salgshistorik/show.html.twig', array(
            'salgshistorik' => $salgshistorik,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Salgshistorik entity.
     *
     * @Route("/{id}/edit", name="salgshistorik_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Salgshistorik $salgshistorik )
    {
        $deleteForm = $this->createDeleteForm($salgshistorik);
        $editForm = $this->createForm('AppBundle\Form\SalgshistorikType', $salgshistorik);
        $editForm->handleRequest($request);

        $this->breadcrumbs->addItem($salgshistorik, $this->generateUrl('salgshistorik_show', array('id' => $salgshistorik->getId())));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('salgshistorik_show', array('id' => $salgshistorik->getId())));

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($salgshistorik);
            $em->flush();

            return $this->redirectToRoute('salgshistorik_edit', array('id' => $salgshistorik->getId()));
        }

        return $this->render('salgshistorik/edit.html.twig', array(
            'salgshistorik' => $salgshistorik,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Salgshistorik entity.
     *
     * @Route("/{id}", name="salgshistorik_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Salgshistorik $salgshistorik)
    {
        $form = $this->createDeleteForm($salgshistorik);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($salgshistorik);
            $em->flush();
        }

        return $this->redirectToRoute('salgshistorik_index');
    }

    /**
     * Creates a form to delete a Salgshistorik entity.
     *
     * @param Salgshistorik $salgshistorik The Salgshistorik entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Salgshistorik $salgshistorik)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('salgshistorik_delete', array('id' => $salgshistorik->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
