<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Landinspektoer;
use AppBundle\Form\LandinspektoerType;
use AppBundle\Controller\BaseController;

/**
 * Landinspektoer controller.
 *
 * @Route("/landinspektoer")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class LandinspektoerController extends BaseController
{

  public function init(Request $request) {
    parent::init($request);
    $this->breadcrumbs->addItem('landinspektoer.labels.singular', $this->generateUrl('landinspektoer'));
}


    /**
     * Lists all Landinspektoer entities.
     *
     * @Route("/", name="landinspektoer")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Landinspektoer')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Landinspektoer entity.
     *
     * @Route("/", name="landinspektoer_create")
     * @Method("POST")
     * @Template("AppBundle:Landinspektoer:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Landinspektoer();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('landinspektoer'));

        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Landinspektoer entity.
     *
     * @param Landinspektoer $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Landinspektoer $entity)
    {
        $form = $this->createForm('AppBundle\Form\LandinspektoerType', $entity, array(
            'action' => $this->generateUrl('landinspektoer_create'),
            'method' => 'POST',
        ));

        $this->addUpdate($form, $this->generateUrl('landinspektoer'));

        return $form;
    }

    /**
     * Displays a form to create a new Landinspektoer entity.
     *
     * @Route("/new", name="landinspektoer_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $this->breadcrumbs->addItem('common.add', $this->generateUrl('landinspektoer'));

        $entity = new Landinspektoer();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Landinspektoer entity.
     *
     * @Route("/{id}", name="landinspektoer_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Landinspektoer')->find($id);
        $this->breadcrumbs->addItem($entity, $this->generateUrl('landinspektoer_show', array('id' => $entity->getId())));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Landinspektoer entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Landinspektoer entity.
     *
     * @Route("/{id}/edit", name="landinspektoer_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction(Landinspektoer $entity)
    {
        $this->breadcrumbs->addItem($entity, $this->generateUrl('landinspektoer_show', array('id' => $entity->getId())));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('landinspektoer_show', array('id' => $entity->getId())));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Landinspektoer entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($entity->getId());

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Landinspektoer entity.
    *
    * @param Landinspektoer $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Landinspektoer $entity)
    {
        $form = $this->createForm('AppBundle\Form\LandinspektoerType', $entity, array(
            'action' => $this->generateUrl('landinspektoer_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $this->addUpdate($form, $this->generateUrl('landinspektoer_show', array('id' => $entity->getId())));

        return $form;
    }
    /**
     * Edits an existing Landinspektoer entity.
     *
     * @Route("/{id}", name="landinspektoer_update")
     * @Method("PUT")
     * @Template("AppBundle:Landinspektoer:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Landinspektoer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Landinspektoer entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('landinspektoer'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Landinspektoer entity.
     *
     * @Route("/{id}", name="landinspektoer_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Landinspektoer')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Landinspektoer entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('landinspektoer'));
    }

    /**
     * Creates a form to delete a Landinspektoer entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('landinspektoer_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
