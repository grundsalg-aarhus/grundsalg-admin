<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Lokalsamfund;
use AppBundle\Form\LokalsamfundType;

/**
 * Lokalsamfund controller.
 *
 * @Route("/admin/lokalsamfund")
 */
class LokalsamfundController extends Controller
{
    /**
     * Lists all Lokalsamfund entities.
     *
     * @Route("/", name="admin_lokalsamfund_index")
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
     * @Route("/new", name="admin_lokalsamfund_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $lokalsamfund = new Lokalsamfund();
        $form = $this->createForm('AppBundle\Form\LokalsamfundType', $lokalsamfund);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($lokalsamfund);
            $em->flush();

            return $this->redirectToRoute('admin_lokalsamfund_show', array('id' => $lokalsamfund->getId()));
        }

        return $this->render('lokalsamfund/new.html.twig', array(
            'lokalsamfund' => $lokalsamfund,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Lokalsamfund entity.
     *
     * @Route("/{id}", name="admin_lokalsamfund_show")
     * @Method("GET")
     */
    public function showAction(Lokalsamfund $lokalsamfund)
    {
        $deleteForm = $this->createDeleteForm($lokalsamfund);

        return $this->render('lokalsamfund/show.html.twig', array(
            'lokalsamfund' => $lokalsamfund,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Lokalsamfund entity.
     *
     * @Route("/{id}/edit", name="admin_lokalsamfund_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Lokalsamfund $lokalsamfund)
    {
        $deleteForm = $this->createDeleteForm($lokalsamfund);
        $editForm = $this->createForm('AppBundle\Form\LokalsamfundType', $lokalsamfund);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($lokalsamfund);
            $em->flush();

            return $this->redirectToRoute('admin_lokalsamfund_edit', array('id' => $lokalsamfund->getId()));
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
     * @Route("/{id}", name="admin_lokalsamfund_delete")
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

        return $this->redirectToRoute('admin_lokalsamfund_index');
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
            ->setAction($this->generateUrl('admin_lokalsamfund_delete', array('id' => $lokalsamfund->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
