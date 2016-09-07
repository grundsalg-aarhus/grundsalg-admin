<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Landinspektoer;
use AppBundle\Form\LandinspektoerType;

/**
 * Landinspektoer controller.
 *
 * @Route("/landinspektoer")
 * @Security("has_role('ROLE_ADMIN')")
 */
class LandinspektoerController extends BaseController
{

  public function init(Request $request) {
    parent::init($request);
    $this->breadcrumbs->addItem('landinspektoer.labels.singular', $this->generateUrl('landinspektoer_index'));
  }

    /**
     * Lists all Landinspektoer entities.
     *
     * @Route("/", name="landinspektoer_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $landinspektoers = $em->getRepository('AppBundle:Landinspektoer')->findAll();

        return $this->render('landinspektoer/index.html.twig', array(
            'landinspektoers' => $landinspektoers,
        ));
    }

    /**
     * Creates a new Landinspektoer entity.
     *
     * @Route("/new", name="landinspektoer_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $landinspektoer = new Landinspektoer();
        $form = $this->createForm('AppBundle\Form\LandinspektoerType', $landinspektoer);
        $form->handleRequest($request);

        $this->breadcrumbs->addItem('common.new', $this->generateUrl('landinspektoer_index'));

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($landinspektoer);
            $em->flush();

            return $this->redirectToRoute('landinspektoer_show', array('id' => $landinspektoer->getId()));
        }

        return $this->render('landinspektoer/new.html.twig', array(
            'landinspektoer' => $landinspektoer,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Landinspektoer entity.
     *
     * @Route("/{id}", name="landinspektoer_show")
     * @Method("GET")
     */
    public function showAction(Landinspektoer $landinspektoer)
    {
    $this->breadcrumbs->addItem($landinspektoer, $this->generateUrl('landinspektoer_show', array('id' => $landinspektoer->getId())));

            $deleteForm = $this->createDeleteForm($landinspektoer);

        return $this->render('landinspektoer/show.html.twig', array(
            'landinspektoer' => $landinspektoer,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Landinspektoer entity.
     *
     * @Route("/{id}/edit", name="landinspektoer_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Landinspektoer $landinspektoer )
    {
        $deleteForm = $this->createDeleteForm($landinspektoer);
        $editForm = $this->createForm('AppBundle\Form\LandinspektoerType', $landinspektoer);
        $editForm->handleRequest($request);

        $this->breadcrumbs->addItem($landinspektoer, $this->generateUrl('landinspektoer_show', array('id' => $landinspektoer->getId())));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('landinspektoer_show', array('id' => $landinspektoer->getId())));

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($landinspektoer);
            $em->flush();

            return $this->redirectToRoute('landinspektoer_edit', array('id' => $landinspektoer->getId()));
        }

        return $this->render('landinspektoer/edit.html.twig', array(
            'landinspektoer' => $landinspektoer,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Landinspektoer entity.
     *
     * @Route("/{id}", name="landinspektoer_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Landinspektoer $landinspektoer)
    {
        $form = $this->createDeleteForm($landinspektoer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($landinspektoer);
            $em->flush();
        }

        return $this->redirectToRoute('landinspektoer_index');
    }

    /**
     * Creates a form to delete a Landinspektoer entity.
     *
     * @param Landinspektoer $landinspektoer The Landinspektoer entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Landinspektoer $landinspektoer)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('landinspektoer_delete', array('id' => $landinspektoer->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
