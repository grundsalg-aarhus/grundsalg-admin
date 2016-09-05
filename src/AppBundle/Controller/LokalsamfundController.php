<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Lokalsamfund;
use AppBundle\Form\LokalsamfundType;
use AppBundle\Controller\BaseController;

/**
 * Lokalsamfund controller.
 *
 * @Route("/lokalsamfund")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class LokalsamfundController extends BaseController
{

  public function init(Request $request) {
    parent::init($request);
    $this->breadcrumbs->addItem('lokalsamfund.labels.singular', $this->generateUrl('lokalsamfund'));
}


    /**
     * Lists all Lokalsamfund entities.
     *
     * @Route("/", name="lokalsamfund")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Lokalsamfund')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Lokalsamfund entity.
     *
     * @Route("/", name="lokalsamfund_create")
     * @Method("POST")
     * @Template("AppBundle:Lokalsamfund:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Lokalsamfund();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('lokalsamfund'));

        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Lokalsamfund entity.
     *
     * @param Lokalsamfund $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Lokalsamfund $entity)
    {
        $form = $this->createForm('AppBundle\Form\LokalsamfundType', $entity, array(
            'action' => $this->generateUrl('lokalsamfund_create'),
            'method' => 'POST',
        ));

        $this->addUpdate($form, $this->generateUrl('lokalsamfund'));

        return $form;
    }

    /**
     * Displays a form to create a new Lokalsamfund entity.
     *
     * @Route("/new", name="lokalsamfund_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $this->breadcrumbs->addItem('common.add', $this->generateUrl('lokalsamfund'));

        $entity = new Lokalsamfund();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Lokalsamfund entity.
     *
     * @Route("/{id}", name="lokalsamfund_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Lokalsamfund')->find($id);
        $this->breadcrumbs->addItem($entity, $this->generateUrl('lokalsamfund_show', array('id' => $entity->getId())));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Lokalsamfund entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Lokalsamfund entity.
     *
     * @Route("/{id}/edit", name="lokalsamfund_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction(Lokalsamfund $entity)
    {
        $this->breadcrumbs->addItem($entity, $this->generateUrl('lokalsamfund_show', array('id' => $entity->getId())));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('lokalsamfund_show', array('id' => $entity->getId())));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Lokalsamfund entity.');
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
    * Creates a form to edit a Lokalsamfund entity.
    *
    * @param Lokalsamfund $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Lokalsamfund $entity)
    {
        $form = $this->createForm('AppBundle\Form\LokalsamfundType', $entity, array(
            'action' => $this->generateUrl('lokalsamfund_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $this->addUpdate($form, $this->generateUrl('lokalsamfund_show', array('id' => $entity->getId())));

        return $form;
    }
    /**
     * Edits an existing Lokalsamfund entity.
     *
     * @Route("/{id}", name="lokalsamfund_update")
     * @Method("PUT")
     * @Template("AppBundle:Lokalsamfund:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Lokalsamfund')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Lokalsamfund entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('lokalsamfund'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Lokalsamfund entity.
     *
     * @Route("/{id}", name="lokalsamfund_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Lokalsamfund')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Lokalsamfund entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('lokalsamfund'));
    }

    /**
     * Creates a form to delete a Lokalsamfund entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('lokalsamfund_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
