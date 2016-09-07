<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Keywordvalue;
use AppBundle\Form\KeywordvalueType;

/**
 * Keywordvalue controller.
 *
 * @Route("/keywordvalue")
 */
class KeywordvalueController extends Controller
{
    /**
     * Lists all Keywordvalue entities.
     *
     * @Route("/", name="keywordvalue_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $keywordvalues = $em->getRepository('AppBundle:Keywordvalue')->findAll();

        return $this->render('keywordvalue/index.html.twig', array(
            'keywordvalues' => $keywordvalues,
        ));
    }

    /**
     * Creates a new Keywordvalue entity.
     *
     * @Route("/new", name="keywordvalue_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $keywordvalue = new Keywordvalue();
        $form = $this->createForm('AppBundle\Form\KeywordvalueType', $keywordvalue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($keywordvalue);
            $em->flush();

            return $this->redirectToRoute('keywordvalue_show', array('id' => $keywordvalue->getId()));
        }

        return $this->render('keywordvalue/new.html.twig', array(
            'keywordvalue' => $keywordvalue,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Keywordvalue entity.
     *
     * @Route("/{id}", name="keywordvalue_show")
     * @Method("GET")
     */
    public function showAction(Keywordvalue $keywordvalue)
    {
        $deleteForm = $this->createDeleteForm($keywordvalue);

        return $this->render('keywordvalue/show.html.twig', array(
            'keywordvalue' => $keywordvalue,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Keywordvalue entity.
     *
     * @Route("/{id}/edit", name="keywordvalue_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Keywordvalue $keywordvalue)
    {
        $deleteForm = $this->createDeleteForm($keywordvalue);
        $editForm = $this->createForm('AppBundle\Form\KeywordvalueType', $keywordvalue);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($keywordvalue);
            $em->flush();

            return $this->redirectToRoute('keywordvalue_edit', array('id' => $keywordvalue->getId()));
        }

        return $this->render('keywordvalue/edit.html.twig', array(
            'keywordvalue' => $keywordvalue,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Keywordvalue entity.
     *
     * @Route("/{id}", name="keywordvalue_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Keywordvalue $keywordvalue)
    {
        $form = $this->createDeleteForm($keywordvalue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($keywordvalue);
            $em->flush();
        }

        return $this->redirectToRoute('keywordvalue_index');
    }

    /**
     * Creates a form to delete a Keywordvalue entity.
     *
     * @param Keywordvalue $keywordvalue The Keywordvalue entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Keywordvalue $keywordvalue)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('keywordvalue_delete', array('id' => $keywordvalue->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
