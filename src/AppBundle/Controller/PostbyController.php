<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Postby;
use AppBundle\Form\PostbyType;

/**
 * Postby controller.
 *
 * @Route("/admin/postby")
 */
class PostbyController extends Controller
{
    /**
     * Lists all Postby entities.
     *
     * @Route("/", name="admin_postby_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $postbies = $em->getRepository('AppBundle:Postby')->findAll();

        return $this->render('postby/index.html.twig', array(
            'postbies' => $postbies,
        ));
    }

    /**
     * Creates a new Postby entity.
     *
     * @Route("/new", name="admin_postby_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $postby = new Postby();
        $form = $this->createForm('AppBundle\Form\PostbyType', $postby);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($postby);
            $em->flush();

            return $this->redirectToRoute('admin_postby_show', array('id' => $postby->getId()));
        }

        return $this->render('postby/new.html.twig', array(
            'postby' => $postby,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Postby entity.
     *
     * @Route("/{id}", name="admin_postby_show")
     * @Method("GET")
     */
    public function showAction(Postby $postby)
    {
        $deleteForm = $this->createDeleteForm($postby);

        return $this->render('postby/show.html.twig', array(
            'postby' => $postby,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Postby entity.
     *
     * @Route("/{id}/edit", name="admin_postby_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Postby $postby)
    {
        $deleteForm = $this->createDeleteForm($postby);
        $editForm = $this->createForm('AppBundle\Form\PostbyType', $postby);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($postby);
            $em->flush();

            return $this->redirectToRoute('admin_postby_edit', array('id' => $postby->getId()));
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
     * @Route("/{id}", name="admin_postby_delete")
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

        return $this->redirectToRoute('admin_postby_index');
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
            ->setAction($this->generateUrl('admin_postby_delete', array('id' => $postby->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
