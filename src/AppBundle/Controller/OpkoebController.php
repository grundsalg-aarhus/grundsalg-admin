<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Opkoeb;
use AppBundle\Form\OpkoebType;

/**
 * Opkoeb controller.
 *
 * @Route("/admin/opkoeb")
 */
class OpkoebController extends Controller
{
    /**
     * Lists all Opkoeb entities.
     *
     * @Route("/", name="admin_opkoeb_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $opkoebs = $em->getRepository('AppBundle:Opkoeb')->findAll();

        return $this->render('opkoeb/index.html.twig', array(
            'opkoebs' => $opkoebs,
        ));
    }

    /**
     * Creates a new Opkoeb entity.
     *
     * @Route("/new", name="admin_opkoeb_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $opkoeb = new Opkoeb();
        $form = $this->createForm('AppBundle\Form\OpkoebType', $opkoeb);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($opkoeb);
            $em->flush();

            return $this->redirectToRoute('admin_opkoeb_show', array('id' => $opkoeb->getId()));
        }

        return $this->render('opkoeb/new.html.twig', array(
            'opkoeb' => $opkoeb,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Opkoeb entity.
     *
     * @Route("/{id}", name="admin_opkoeb_show")
     * @Method("GET")
     */
    public function showAction(Opkoeb $opkoeb)
    {
        $deleteForm = $this->createDeleteForm($opkoeb);

        return $this->render('opkoeb/show.html.twig', array(
            'opkoeb' => $opkoeb,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Opkoeb entity.
     *
     * @Route("/{id}/edit", name="admin_opkoeb_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Opkoeb $opkoeb)
    {
        $deleteForm = $this->createDeleteForm($opkoeb);
        $editForm = $this->createForm('AppBundle\Form\OpkoebType', $opkoeb);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($opkoeb);
            $em->flush();

            return $this->redirectToRoute('admin_opkoeb_edit', array('id' => $opkoeb->getId()));
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
     * @Route("/{id}", name="admin_opkoeb_delete")
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

        return $this->redirectToRoute('admin_opkoeb_index');
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
            ->setAction($this->generateUrl('admin_opkoeb_delete', array('id' => $opkoeb->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
