<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Lokalsamfund;
use AppBundle\Form\LokalsamfundType;

/**
 * Lokalsamfund controller.
 *
 * @Route("/lokalsamfund")
 * @Security("has_role('ROLE_ADMIN')")
 */
class LokalsamfundController extends BaseController
{

  public function init(Request $request) {
    parent::init($request);
    $this->breadcrumbs->addItem('lokalsamfund.labels.singular', $this->generateUrl('lokalsamfund_index'));
  }

    /**
     * Lists all Lokalsamfund entities.
     *
     * @Route("/", name="lokalsamfund_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $lokalsamfunds = $em->getRepository('AppBundle:Lokalsamfund')->findAll();

        return $this->render('lokalsamfund/index.html.twig', array(
            'lokalsamfunds' => $lokalsamfunds,
        ));
    }

    /**
     * Creates a new Lokalsamfund entity.
     *
     * @Route("/new", name="lokalsamfund_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $lokalsamfund = new Lokalsamfund();
        $form = $this->createForm('AppBundle\Form\LokalsamfundType', $lokalsamfund);
        $form->handleRequest($request);

        $this->breadcrumbs->addItem('common.new', $this->generateUrl('lokalsamfund_index'));

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($lokalsamfund);
            $em->flush();

            return $this->redirectToRoute('lokalsamfund_show', array('id' => $lokalsamfund->getId()));
        }

        return $this->render('lokalsamfund/new.html.twig', array(
            'lokalsamfund' => $lokalsamfund,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Lokalsamfund entity.
     *
     * @Route("/{id}", name="lokalsamfund_show")
     * @Method("GET")
     */
    public function showAction(Lokalsamfund $lokalsamfund)
    {
    $this->breadcrumbs->addItem($lokalsamfund, $this->generateUrl('lokalsamfund_show', array('id' => $lokalsamfund->getId())));

            $deleteForm = $this->createDeleteForm($lokalsamfund);

        return $this->render('lokalsamfund/show.html.twig', array(
            'lokalsamfund' => $lokalsamfund,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Lokalsamfund entity.
     *
     * @Route("/{id}/edit", name="lokalsamfund_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Lokalsamfund $lokalsamfund )
    {
        $deleteForm = $this->createDeleteForm($lokalsamfund);
        $editForm = $this->createForm('AppBundle\Form\LokalsamfundType', $lokalsamfund);
        $editForm->handleRequest($request);

        $this->breadcrumbs->addItem($lokalsamfund, $this->generateUrl('lokalsamfund_show', array('id' => $lokalsamfund->getId())));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('lokalsamfund_show', array('id' => $lokalsamfund->getId())));

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($lokalsamfund);
            $em->flush();

            return $this->redirectToRoute('lokalsamfund_edit', array('id' => $lokalsamfund->getId()));
        }

        return $this->render('lokalsamfund/edit.html.twig', array(
            'lokalsamfund' => $lokalsamfund,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Lokalsamfund entity.
     *
     * @Route("/{id}", name="lokalsamfund_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Lokalsamfund $lokalsamfund)
    {
        $form = $this->createDeleteForm($lokalsamfund);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($lokalsamfund);
            $em->flush();
        }

        return $this->redirectToRoute('lokalsamfund_index');
    }

    /**
     * Creates a form to delete a Lokalsamfund entity.
     *
     * @param Lokalsamfund $lokalsamfund The Lokalsamfund entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Lokalsamfund $lokalsamfund)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('lokalsamfund_delete', array('id' => $lokalsamfund->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
