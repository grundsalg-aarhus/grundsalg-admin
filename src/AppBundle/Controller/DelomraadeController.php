<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Delomraade;
use AppBundle\Form\DelomraadeType;

/**
 * Delomraade controller.
 *
 * @Route("/admin/delomraade")
 */
class DelomraadeController extends Controller
{
    /**
     * Lists all Delomraade entities.
     *
     * @Route("/", name="admin_delomraade_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $delomraades = $em->getRepository('AppBundle:Delomraade')->findAll();

        return $this->render('delomraade/index.html.twig', array(
            'delomraades' => $delomraades,
        ));
    }

    /**
     * Creates a new Delomraade entity.
     *
     * @Route("/new", name="admin_delomraade_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $delomraade = new Delomraade();
        $form = $this->createForm('AppBundle\Form\DelomraadeType', $delomraade);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($delomraade);
            $em->flush();

            return $this->redirectToRoute('admin_delomraade_show', array('id' => $delomraade->getId()));
        }

        return $this->render('delomraade/new.html.twig', array(
            'delomraade' => $delomraade,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Delomraade entity.
     *
     * @Route("/{id}", name="admin_delomraade_show")
     * @Method("GET")
     */
    public function showAction(Delomraade $delomraade)
    {
        $deleteForm = $this->createDeleteForm($delomraade);

        return $this->render('delomraade/show.html.twig', array(
            'delomraade' => $delomraade,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Delomraade entity.
     *
     * @Route("/{id}/edit", name="admin_delomraade_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Delomraade $delomraade)
    {
        $deleteForm = $this->createDeleteForm($delomraade);
        $editForm = $this->createForm('AppBundle\Form\DelomraadeType', $delomraade);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($delomraade);
            $em->flush();

            return $this->redirectToRoute('admin_delomraade_edit', array('id' => $delomraade->getId()));
        }

        return $this->render('delomraade/edit.html.twig', array(
            'delomraade' => $delomraade,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Delomraade entity.
     *
     * @Route("/{id}", name="admin_delomraade_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Delomraade $delomraade)
    {
        $form = $this->createDeleteForm($delomraade);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($delomraade);
            $em->flush();
        }

        return $this->redirectToRoute('admin_delomraade_index');
    }

    /**
     * Creates a form to delete a Delomraade entity.
     *
     * @param Delomraade $delomraade The Delomraade entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Delomraade $delomraade)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_delomraade_delete', array('id' => $delomraade->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
