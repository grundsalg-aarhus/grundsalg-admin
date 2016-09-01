<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Lokalplan;
use AppBundle\Form\LokalplanType;

/**
 * Lokalplan controller.
 *
 * @Route("/admin/lokalplan")
 */
class LokalplanController extends Controller
{
    /**
     * Lists all Lokalplan entities.
     *
     * @Route("/", name="admin_lokalplan_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $lokalplans = $em->getRepository('AppBundle:Lokalplan')->findAll();

        return $this->render('lokalplan/index.html.twig', array(
            'lokalplans' => $lokalplans,
        ));
    }

    /**
     * Creates a new Lokalplan entity.
     *
     * @Route("/new", name="admin_lokalplan_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $lokalplan = new Lokalplan();
        $form = $this->createForm('AppBundle\Form\LokalplanType', $lokalplan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($lokalplan);
            $em->flush();

            return $this->redirectToRoute('admin_lokalplan_show', array('id' => $lokalplan->getId()));
        }

        return $this->render('lokalplan/new.html.twig', array(
            'lokalplan' => $lokalplan,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Lokalplan entity.
     *
     * @Route("/{id}", name="admin_lokalplan_show")
     * @Method("GET")
     */
    public function showAction(Lokalplan $lokalplan)
    {
        $deleteForm = $this->createDeleteForm($lokalplan);

        return $this->render('lokalplan/show.html.twig', array(
            'lokalplan' => $lokalplan,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Lokalplan entity.
     *
     * @Route("/{id}/edit", name="admin_lokalplan_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Lokalplan $lokalplan)
    {
        $deleteForm = $this->createDeleteForm($lokalplan);
        $editForm = $this->createForm('AppBundle\Form\LokalplanType', $lokalplan);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($lokalplan);
            $em->flush();

            return $this->redirectToRoute('admin_lokalplan_edit', array('id' => $lokalplan->getId()));
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
     * @Route("/{id}", name="admin_lokalplan_delete")
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

        return $this->redirectToRoute('admin_lokalplan_index');
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
            ->setAction($this->generateUrl('admin_lokalplan_delete', array('id' => $lokalplan->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
