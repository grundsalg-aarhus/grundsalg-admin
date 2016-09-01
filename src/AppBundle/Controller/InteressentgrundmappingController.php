<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Interessentgrundmapping;
use AppBundle\Form\InteressentgrundmappingType;

/**
 * Interessentgrundmapping controller.
 *
 * @Route("/admin/interessentgrundmapping")
 */
class InteressentgrundmappingController extends Controller
{
    /**
     * Lists all Interessentgrundmapping entities.
     *
     * @Route("/", name="admin_interessentgrundmapping_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $interessentgrundmappings = $em->getRepository('AppBundle:Interessentgrundmapping')->findAll();

        return $this->render('interessentgrundmapping/index.html.twig', array(
            'interessentgrundmappings' => $interessentgrundmappings,
        ));
    }

    /**
     * Creates a new Interessentgrundmapping entity.
     *
     * @Route("/new", name="admin_interessentgrundmapping_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $interessentgrundmapping = new Interessentgrundmapping();
        $form = $this->createForm('AppBundle\Form\InteressentgrundmappingType', $interessentgrundmapping);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($interessentgrundmapping);
            $em->flush();

            return $this->redirectToRoute('admin_interessentgrundmapping_show', array('id' => $interessentgrundmapping->getId()));
        }

        return $this->render('interessentgrundmapping/new.html.twig', array(
            'interessentgrundmapping' => $interessentgrundmapping,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Interessentgrundmapping entity.
     *
     * @Route("/{id}", name="admin_interessentgrundmapping_show")
     * @Method("GET")
     */
    public function showAction(Interessentgrundmapping $interessentgrundmapping)
    {
        $deleteForm = $this->createDeleteForm($interessentgrundmapping);

        return $this->render('interessentgrundmapping/show.html.twig', array(
            'interessentgrundmapping' => $interessentgrundmapping,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Interessentgrundmapping entity.
     *
     * @Route("/{id}/edit", name="admin_interessentgrundmapping_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Interessentgrundmapping $interessentgrundmapping)
    {
        $deleteForm = $this->createDeleteForm($interessentgrundmapping);
        $editForm = $this->createForm('AppBundle\Form\InteressentgrundmappingType', $interessentgrundmapping);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($interessentgrundmapping);
            $em->flush();

            return $this->redirectToRoute('admin_interessentgrundmapping_edit', array('id' => $interessentgrundmapping->getId()));
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
     * @Route("/{id}", name="admin_interessentgrundmapping_delete")
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

        return $this->redirectToRoute('admin_interessentgrundmapping_index');
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
            ->setAction($this->generateUrl('admin_interessentgrundmapping_delete', array('id' => $interessentgrundmapping->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
