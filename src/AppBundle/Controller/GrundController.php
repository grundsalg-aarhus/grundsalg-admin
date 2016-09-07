<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Grund;
use AppBundle\Form\GrundType;

/**
 * Grund controller.
 *
 * @Route("/grund")
 * @Security("has_role('ROLE_ADMIN')")
 */
class GrundController extends BaseController
{

  public function init(Request $request) {
    parent::init($request);
    $this->breadcrumbs->addItem('grund.labels.singular', $this->generateUrl('grund_index'));
  }

    /**
     * Lists all Grund entities.
     *
     * @Route("/", name="grund_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $grunds = $em->getRepository('AppBundle:Grund')->findAll();

        return $this->render('grund/index.html.twig', array(
            'grunds' => $grunds,
        ));
    }

    /**
     * Creates a new Grund entity.
     *
     * @Route("/new", name="grund_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $grund = new Grund();
        $form = $this->createForm('AppBundle\Form\GrundType', $grund);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($grund);
            $em->flush();

            return $this->redirectToRoute('grund_show', array('id' => $grund->getId()));
        }

        return $this->render('grund/new.html.twig', array(
            'grund' => $grund,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Grund entity.
     *
     * @Route("/{id}", name="grund_show")
     * @Method("GET")
     */
    public function showAction(Grund $grund)
    {
        $deleteForm = $this->createDeleteForm($grund);

        return $this->render('grund/show.html.twig', array(
            'grund' => $grund,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Grund entity.
     *
     * @Route("/{id}/edit", name="grund_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Grund $grund)
    {
        $deleteForm = $this->createDeleteForm($grund);
        $editForm = $this->createForm('AppBundle\Form\GrundType', $grund);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($grund);
            $em->flush();

            return $this->redirectToRoute('grund_edit', array('id' => $grund->getId()));
        }

        return $this->render('grund/edit.html.twig', array(
            'grund' => $grund,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Grund entity.
     *
     * @Route("/{id}", name="grund_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Grund $grund)
    {
        $form = $this->createDeleteForm($grund);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($grund);
            $em->flush();
        }

        return $this->redirectToRoute('grund_index');
    }

    /**
     * Creates a form to delete a Grund entity.
     *
     * @param Grund $grund The Grund entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Grund $grund)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('grund_delete', array('id' => $grund->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
